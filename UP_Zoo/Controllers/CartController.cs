using System.Data;
using System.Security.Claims;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;
using UP_Zoo.Services;

namespace UP_Zoo.Controllers;

public class CartController(AppDbContext db) : Controller
{
    public async Task<IActionResult> Index(CancellationToken cancellationToken)
    {
        var items = await CartSession.LoadAsync(HttpContext.Session, db, cancellationToken);
        ViewBag.Total = items.Sum(item => item.Subtotal);
        return View(items);
    }

    [HttpPost]
    public async Task<IActionResult> Add(int id, int quantity = 1, CancellationToken cancellationToken = default)
    {
        if (quantity is < 1 or > 99)
        {
            return BadRequest();
        }

        var product = await db.Products.FindAsync([id], cancellationToken);
        if (product is null)
        {
            return NotFound();
        }

        if (quantity > product.Stock)
        {
            TempData["Error"] = "Такого количества сейчас нет в наличии.";
            return RedirectToAction("Index", "Products");
        }

        var quantities = CartSession.GetQuantities(HttpContext.Session);
        quantities[id] = Math.Min(product.Stock, quantities.GetValueOrDefault(id) + quantity);
        CartSession.SaveQuantities(HttpContext.Session, quantities);
        TempData["Success"] = "Товар добавлен в корзину.";
        return RedirectToAction("Index", "Products");
    }

    [HttpPost]
    public async Task<IActionResult> Update(int id, int quantity, CancellationToken cancellationToken)
    {
        var product = await db.Products.FindAsync([id], cancellationToken);
        if (product is null)
        {
            return NotFound();
        }

        if (quantity is < 1 or > 99 || quantity > product.Stock)
        {
            TempData["Error"] = "Укажите доступное количество товара.";
            return RedirectToAction(nameof(Index));
        }

        var quantities = CartSession.GetQuantities(HttpContext.Session);
        if (quantities.ContainsKey(id))
        {
            quantities[id] = quantity;
            CartSession.SaveQuantities(HttpContext.Session, quantities);
        }

        return RedirectToAction(nameof(Index));
    }

    [HttpPost]
    public IActionResult Remove(int id)
    {
        var quantities = CartSession.GetQuantities(HttpContext.Session);
        quantities.Remove(id);
        CartSession.SaveQuantities(HttpContext.Session, quantities);
        return RedirectToAction(nameof(Index));
    }

    [HttpGet]
    public async Task<IActionResult> Checkout(CancellationToken cancellationToken)
    {
        var items = await CartSession.LoadAsync(HttpContext.Session, db, cancellationToken);
        if (items.Count == 0)
        {
            return RedirectToAction(nameof(Index));
        }

        ViewBag.Total = items.Sum(item => item.Subtotal);
        return View(new CheckoutViewModel
        {
            CustomerName = User.Identity?.IsAuthenticated == true ? User.Identity.Name ?? string.Empty : string.Empty,
            Email = User.FindFirstValue(ClaimTypes.Email) ?? string.Empty
        });
    }

    [HttpPost]
    public async Task<IActionResult> Checkout(CheckoutViewModel model, CancellationToken cancellationToken)
    {
        var quantities = CartSession.GetQuantities(HttpContext.Session);
        if (quantities.Count == 0)
        {
            return RedirectToAction(nameof(Index));
        }

        if (!ModelState.IsValid)
        {
            var currentItems = await CartSession.LoadAsync(HttpContext.Session, db, cancellationToken);
            ViewBag.Total = currentItems.Sum(item => item.Subtotal);
            return View(model);
        }

        await using var transaction = await db.Database.BeginTransactionAsync(IsolationLevel.ReadCommitted, cancellationToken);
        var products = await db.Products.Where(product => quantities.Keys.Contains(product.Id))
            .ToDictionaryAsync(product => product.Id, cancellationToken);
        if (products.Count != quantities.Count || quantities.Any(pair => products[pair.Key].Stock < pair.Value))
        {
            ModelState.AddModelError(string.Empty, "Один из товаров закончился или изменилось доступное количество.");
            var currentItems = await CartSession.LoadAsync(HttpContext.Session, db, cancellationToken);
            ViewBag.Total = currentItems.Sum(item => item.Subtotal);
            return View(model);
        }

        var order = new ShopOrder
        {
            UserId = User.FindFirstValue(ClaimTypes.NameIdentifier),
            CustomerName = model.CustomerName,
            Email = model.Email,
            Phone = model.Phone,
            Address = model.Address,
            Notes = model.Notes,
            Items = quantities.Select(pair =>
            {
                var product = products[pair.Key];
                product.Stock -= pair.Value;
                return new OrderItem
                {
                    ProductId = product.Id,
                    ProductName = product.Name,
                    Price = product.CurrentPrice,
                    Quantity = pair.Value
                };
            }).ToList()
        };
        order.Total = order.Items.Sum(item => item.Price * item.Quantity);
        db.ShopOrders.Add(order);
        await db.SaveChangesAsync(cancellationToken);
        await transaction.CommitAsync(cancellationToken);
        CartSession.SaveQuantities(HttpContext.Session, []);
        TempData["Success"] = $"Заказ №{order.Id} оформлен.";
        return RedirectToAction(User.Identity?.IsAuthenticated == true ? "Index" : "Index",
            User.Identity?.IsAuthenticated == true ? "Account" : "Home");
    }
}
