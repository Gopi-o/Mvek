using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Controllers;

[Route("admin")]
[Authorize(Roles = ShopRoles.Admin + "," + ShopRoles.Manager)]
public class AdminController(
    AppDbContext db,
    UserManager<AppUser> userManager,
    RoleManager<IdentityRole> roleManager) : Controller
{
    [HttpGet("")]
    public async Task<IActionResult> Index(CancellationToken cancellationToken)
    {
        ViewBag.ProductCount = await db.Products.CountAsync(cancellationToken);
        ViewBag.OrderCount = await db.ShopOrders.CountAsync(cancellationToken);
        ViewBag.NewOrderCount = await db.ShopOrders.CountAsync(order => order.Status == OrderStatuses.New, cancellationToken);
        ViewBag.PendingReviewCount = await db.Reviews.CountAsync(review => !review.IsApproved, cancellationToken);
        return View(await db.ShopOrders.OrderByDescending(order => order.CreatedAt).Take(6).ToListAsync(cancellationToken));
    }

    [HttpGet("products")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> Products(CancellationToken cancellationToken) =>
        View(await db.Products.Include(product => product.Category).Include(product => product.Brand)
            .OrderBy(product => product.Name).ToListAsync(cancellationToken));

    [HttpGet("products/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> CreateProduct(CancellationToken cancellationToken)
    {
        await LoadProductOptions(cancellationToken);
        return View("ProductForm", new Product());
    }

    [HttpPost("products/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> CreateProduct(
        [Bind("Name,Slug,Description,CategoryId,BrandId,Price,SalePrice,Stock,ImageUrl,IsNew,IsFeatured")] Product product,
        CancellationToken cancellationToken)
    {
        ValidateSalePrice(product);
        if (!ModelState.IsValid)
        {
            await LoadProductOptions(cancellationToken);
            return View("ProductForm", product);
        }

        db.Products.Add(product);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Товар добавлен.";
        return RedirectToAction(nameof(Products));
    }

    [HttpGet("products/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditProduct(int id, CancellationToken cancellationToken)
    {
        var product = await db.Products.FindAsync([id], cancellationToken);
        if (product is null) return NotFound();
        await LoadProductOptions(cancellationToken);
        return View("ProductForm", product);
    }

    [HttpPost("products/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditProduct(
        int id,
        [Bind("Id,Name,Slug,Description,CategoryId,BrandId,Price,SalePrice,Stock,ImageUrl,IsNew,IsFeatured")] Product input,
        CancellationToken cancellationToken)
    {
        if (id != input.Id) return BadRequest();
        ValidateSalePrice(input);
        if (!ModelState.IsValid)
        {
            await LoadProductOptions(cancellationToken);
            return View("ProductForm", input);
        }

        var product = await db.Products.FindAsync([id], cancellationToken);
        if (product is null) return NotFound();
        CopyProduct(input, product);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Товар обновлён.";
        return RedirectToAction(nameof(Products));
    }

    [HttpPost("products/{id:int}/delete")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> DeleteProduct(int id, CancellationToken cancellationToken)
    {
        var product = await db.Products.FindAsync([id], cancellationToken);
        if (product is null) return NotFound();
        db.Products.Remove(product);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Товар удалён.";
        return RedirectToAction(nameof(Products));
    }

    [HttpGet("categories")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> Categories(CancellationToken cancellationToken) =>
        View(await db.Categories.Include(category => category.Products).OrderBy(category => category.Name).ToListAsync(cancellationToken));

    [HttpGet("categories/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public IActionResult CreateCategory() => View("CategoryForm", new Category());

    [HttpPost("categories/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> CreateCategory(
        [Bind("Name,Slug,Icon,Description")] Category category,
        CancellationToken cancellationToken)
    {
        if (!ModelState.IsValid) return View("CategoryForm", category);
        db.Categories.Add(category);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Категория добавлена.";
        return RedirectToAction(nameof(Categories));
    }

    [HttpGet("categories/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditCategory(int id, CancellationToken cancellationToken)
    {
        var category = await db.Categories.FindAsync([id], cancellationToken);
        return category is null ? NotFound() : View("CategoryForm", category);
    }

    [HttpPost("categories/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditCategory(
        int id,
        [Bind("Id,Name,Slug,Icon,Description")] Category input,
        CancellationToken cancellationToken)
    {
        if (id != input.Id) return BadRequest();
        if (!ModelState.IsValid) return View("CategoryForm", input);
        var category = await db.Categories.FindAsync([id], cancellationToken);
        if (category is null) return NotFound();
        category.Name = input.Name;
        category.Slug = input.Slug;
        category.Icon = input.Icon;
        category.Description = input.Description;
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Категория обновлена.";
        return RedirectToAction(nameof(Categories));
    }

    [HttpPost("categories/{id:int}/delete")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> DeleteCategory(int id, CancellationToken cancellationToken)
    {
        var category = await db.Categories.FindAsync([id], cancellationToken);
        if (category is null) return NotFound();
        db.Categories.Remove(category);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Категория удалена.";
        return RedirectToAction(nameof(Categories));
    }

    [HttpGet("brands")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> Brands(CancellationToken cancellationToken) =>
        View(await db.Brands.Include(brand => brand.Products).OrderBy(brand => brand.Name).ToListAsync(cancellationToken));

    [HttpGet("brands/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public IActionResult CreateBrand() => View("BrandForm", new Brand());

    [HttpPost("brands/create")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> CreateBrand(
        [Bind("Name,Slug,Description")] Brand brand,
        CancellationToken cancellationToken)
    {
        if (!ModelState.IsValid) return View("BrandForm", brand);
        db.Brands.Add(brand);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Бренд добавлен.";
        return RedirectToAction(nameof(Brands));
    }

    [HttpGet("brands/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditBrand(int id, CancellationToken cancellationToken)
    {
        var brand = await db.Brands.FindAsync([id], cancellationToken);
        return brand is null ? NotFound() : View("BrandForm", brand);
    }

    [HttpPost("brands/{id:int}/edit")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> EditBrand(
        int id,
        [Bind("Id,Name,Slug,Description")] Brand input,
        CancellationToken cancellationToken)
    {
        if (id != input.Id) return BadRequest();
        if (!ModelState.IsValid) return View("BrandForm", input);
        var brand = await db.Brands.FindAsync([id], cancellationToken);
        if (brand is null) return NotFound();
        brand.Name = input.Name;
        brand.Slug = input.Slug;
        brand.Description = input.Description;
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Бренд обновлён.";
        return RedirectToAction(nameof(Brands));
    }

    [HttpPost("brands/{id:int}/delete")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> DeleteBrand(int id, CancellationToken cancellationToken)
    {
        var brand = await db.Brands.FindAsync([id], cancellationToken);
        if (brand is null) return NotFound();
        db.Brands.Remove(brand);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Бренд удалён.";
        return RedirectToAction(nameof(Brands));
    }

    [HttpGet("orders")]
    public async Task<IActionResult> Orders(CancellationToken cancellationToken) =>
        View(await db.ShopOrders.OrderByDescending(order => order.CreatedAt).ToListAsync(cancellationToken));

    [HttpGet("orders/{id:int}")]
    public async Task<IActionResult> Order(int id, CancellationToken cancellationToken)
    {
        var order = await db.ShopOrders.Include(item => item.Items).FirstOrDefaultAsync(item => item.Id == id, cancellationToken);
        return order is null ? NotFound() : View(order);
    }

    [HttpPost("orders/{id:int}/status")]
    public async Task<IActionResult> UpdateOrderStatus(int id, string status, CancellationToken cancellationToken)
    {
        if (!OrderStatuses.Labels.ContainsKey(status)) return BadRequest();
        var order = await db.ShopOrders.FindAsync([id], cancellationToken);
        if (order is null) return NotFound();
        order.Status = status;
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Статус заказа обновлён.";
        return RedirectToAction(nameof(Order), new { id });
    }

    [HttpGet("reviews")]
    public async Task<IActionResult> Reviews(CancellationToken cancellationToken) =>
        View(await db.Reviews.Include(review => review.Product).OrderByDescending(review => review.CreatedAt).ToListAsync(cancellationToken));

    [HttpPost("reviews/{id:int}/moderate")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> ModerateReview(int id, bool isApproved, CancellationToken cancellationToken)
    {
        var review = await db.Reviews.FindAsync([id], cancellationToken);
        if (review is null) return NotFound();
        review.IsApproved = isApproved;
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Статус отзыва изменён.";
        return RedirectToAction(nameof(Reviews));
    }

    [HttpPost("reviews/{id:int}/delete")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> DeleteReview(int id, CancellationToken cancellationToken)
    {
        var review = await db.Reviews.FindAsync([id], cancellationToken);
        if (review is null) return NotFound();
        db.Reviews.Remove(review);
        await db.SaveChangesAsync(cancellationToken);
        TempData["Success"] = "Отзыв удалён.";
        return RedirectToAction(nameof(Reviews));
    }

    [HttpGet("users")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> Users(CancellationToken cancellationToken)
    {
        var users = await userManager.Users.OrderBy(user => user.Email).ToListAsync(cancellationToken);
        ViewBag.Roles = await roleManager.Roles.Select(role => role.Name!).ToListAsync(cancellationToken);
        var userRoles = new Dictionary<string, string>();
        foreach (var user in users)
        {
            var roles = await userManager.GetRolesAsync(user);
            userRoles[user.Id] = roles.FirstOrDefault() ?? ShopRoles.Customer;
        }

        ViewBag.UserRoles = userRoles;
        return View(users);
    }

    [HttpPost("users/{id}/role")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> UpdateRole(string id, string role)
    {
        if (!await roleManager.RoleExistsAsync(role)) return BadRequest();
        var target = await userManager.FindByIdAsync(id);
        if (target is null) return NotFound();
        if (target.Id == userManager.GetUserId(User) && role != ShopRoles.Admin)
        {
            TempData["Error"] = "Нельзя снять роль администратора со своей учётной записи.";
            return RedirectToAction(nameof(Users));
        }

        if (await userManager.IsInRoleAsync(target, ShopRoles.Admin) && role != ShopRoles.Admin &&
            (await userManager.GetUsersInRoleAsync(ShopRoles.Admin)).Count <= 1)
        {
            TempData["Error"] = "В системе должен остаться хотя бы один администратор.";
            return RedirectToAction(nameof(Users));
        }

        var oldRoles = await userManager.GetRolesAsync(target);
        EnsureSucceeded(await userManager.RemoveFromRolesAsync(target, oldRoles));
        EnsureSucceeded(await userManager.AddToRoleAsync(target, role));
        TempData["Success"] = "Роль пользователя обновлена.";
        return RedirectToAction(nameof(Users));
    }

    [HttpPost("users/{id}/delete")]
    [Authorize(Roles = ShopRoles.Admin)]
    public async Task<IActionResult> DeleteUser(string id)
    {
        var target = await userManager.FindByIdAsync(id);
        if (target is null) return NotFound();
        if (target.Id == userManager.GetUserId(User) || await userManager.IsInRoleAsync(target, ShopRoles.Admin) &&
            (await userManager.GetUsersInRoleAsync(ShopRoles.Admin)).Count <= 1)
        {
            TempData["Error"] = "Нельзя удалить собственную учётную запись или последнего администратора.";
            return RedirectToAction(nameof(Users));
        }

        EnsureSucceeded(await userManager.DeleteAsync(target));
        TempData["Success"] = "Пользователь удалён.";
        return RedirectToAction(nameof(Users));
    }

    private async Task LoadProductOptions(CancellationToken cancellationToken)
    {
        ViewBag.Categories = await db.Categories.OrderBy(category => category.Name).ToListAsync(cancellationToken);
        ViewBag.Brands = await db.Brands.OrderBy(brand => brand.Name).ToListAsync(cancellationToken);
    }

    private void ValidateSalePrice(Product product)
    {
        if (product.SalePrice.HasValue && product.SalePrice >= product.Price)
        {
            ModelState.AddModelError(nameof(product.SalePrice), "Цена по акции должна быть меньше обычной цены.");
        }
    }

    private static void CopyProduct(Product source, Product destination)
    {
        destination.Name = source.Name;
        destination.Slug = source.Slug;
        destination.Description = source.Description;
        destination.CategoryId = source.CategoryId;
        destination.BrandId = source.BrandId;
        destination.Price = source.Price;
        destination.SalePrice = source.SalePrice;
        destination.Stock = source.Stock;
        destination.ImageUrl = source.ImageUrl;
        destination.IsNew = source.IsNew;
        destination.IsFeatured = source.IsFeatured;
    }

    private static void EnsureSucceeded(IdentityResult result)
    {
        if (!result.Succeeded)
        {
            throw new InvalidOperationException(string.Join("; ", result.Errors.Select(error => error.Description)));
        }
    }
}
