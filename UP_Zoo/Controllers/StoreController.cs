using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Controllers;

public class StoreController(AppDbContext db) : Controller
{
    public IActionResult About() => View();

    public async Task<IActionResult> Promotions(CancellationToken cancellationToken)
    {
        var model = new CatalogViewModel
        {
            Title = "Акции",
            Products = await db.Products.Include(product => product.Category).Include(product => product.Brand)
                .Where(product => product.SalePrice != null).ToListAsync(cancellationToken)
        };

        return View("Products", model);
    }

    public async Task<IActionResult> New(CancellationToken cancellationToken)
    {
        var model = new CatalogViewModel
        {
            Title = "Новинки",
            Products = await db.Products.Include(product => product.Category).Include(product => product.Brand)
                .Where(product => product.IsNew).OrderByDescending(product => product.Id).ToListAsync(cancellationToken)
        };

        return View("Products", model);
    }

    public async Task<IActionResult> Brands(string? brand, CancellationToken cancellationToken)
    {
        ViewBag.Brands = await db.Brands.Include(item => item.Products).OrderBy(item => item.Name).ToListAsync(cancellationToken);
        var query = db.Products.Include(product => product.Category).Include(product => product.Brand).AsQueryable();
        if (!string.IsNullOrWhiteSpace(brand))
        {
            query = query.Where(product => product.Brand != null && product.Brand.Slug == brand);
        }

        return View(await query.OrderBy(product => product.Name).ToListAsync(cancellationToken));
    }

    [HttpGet]
    public IActionResult Contacts() => View();

    [HttpPost]
    public async Task<IActionResult> Contacts(ContactMessage input, CancellationToken cancellationToken)
    {
        if (!ModelState.IsValid)
        {
            return View(input);
        }

        db.ContactMessages.Add(input);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Спасибо! Мы скоро с вами свяжемся.";
        return RedirectToAction(nameof(Contacts));
    }

    public async Task<IActionResult> Reviews(CancellationToken cancellationToken)
    {
        ViewBag.Products = await db.Products.OrderBy(product => product.Name).ToListAsync(cancellationToken);
        return View(await db.Reviews.Include(review => review.Product)
            .Where(review => review.IsApproved).OrderByDescending(review => review.CreatedAt)
            .ToListAsync(cancellationToken));
    }

    [Authorize]
    [HttpPost]
    public async Task<IActionResult> AddReview(CreateReviewViewModel model, CancellationToken cancellationToken)
    {
        ViewBag.Products = await db.Products.OrderBy(product => product.Name).ToListAsync(cancellationToken);
        if (!ModelState.IsValid)
        {
            var reviews = await db.Reviews.Include(review => review.Product)
                .Where(review => review.IsApproved).OrderByDescending(review => review.CreatedAt)
                .ToListAsync(cancellationToken);
            return View("Reviews", reviews);
        }

        var purchased = await db.OrderItems
            .AnyAsync(item => item.ProductId == model.ProductId && item.Order.UserId == User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)!.Value, cancellationToken);
        if (!purchased)
        {
            TempData["Error"] = "Отзыв можно оставить после покупки этого товара.";
            return RedirectToAction(nameof(Reviews));
        }

        db.Reviews.Add(new Review
        {
            ProductId = model.ProductId,
            UserId = User.FindFirst(System.Security.Claims.ClaimTypes.NameIdentifier)!.Value,
            AuthorName = User.Identity?.Name ?? "Покупатель",
            Rating = model.Rating,
            Text = model.Text,
            IsApproved = false
        });
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Спасибо за отзыв! Он появится после проверки.";
        return RedirectToAction(nameof(Reviews));
    }
}
