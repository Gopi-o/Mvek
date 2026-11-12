using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Controllers;

public class ProductsController(AppDbContext db) : Controller
{
    public async Task<IActionResult> Index(
        string? q,
        string? category,
        string? brand,
        decimal? minPrice,
        decimal? maxPrice,
        CancellationToken cancellationToken)
    {
        var query = db.Products.Include(product => product.Category).Include(product => product.Brand).AsQueryable();
        if (!string.IsNullOrWhiteSpace(q))
        {
            query = query.Where(product => product.Name.Contains(q));
        }

        if (!string.IsNullOrWhiteSpace(category))
        {
            query = query.Where(product => product.Category != null && product.Category.Slug == category);
        }

        if (!string.IsNullOrWhiteSpace(brand))
        {
            query = query.Where(product => product.Brand != null && product.Brand.Slug == brand);
        }

        if (minPrice.HasValue)
        {
            query = query.Where(product => (product.SalePrice ?? product.Price) >= minPrice.Value);
        }

        if (maxPrice.HasValue)
        {
            query = query.Where(product => (product.SalePrice ?? product.Price) <= maxPrice.Value);
        }

        return View(new CatalogViewModel
        {
            Products = await query.OrderBy(product => product.Name).ToListAsync(cancellationToken),
            Categories = await db.Categories.OrderBy(item => item.Name).ToListAsync(cancellationToken),
            Brands = await db.Brands.OrderBy(item => item.Name).ToListAsync(cancellationToken),
            Query = q,
            Category = category,
            Brand = brand,
            MinPrice = minPrice,
            MaxPrice = maxPrice
        });
    }

    public async Task<IActionResult> Details(string slug, CancellationToken cancellationToken)
    {
        var product = await db.Products.Include(item => item.Category).Include(item => item.Brand)
            .FirstOrDefaultAsync(item => item.Slug == slug, cancellationToken);
        if (product is null)
        {
            return NotFound();
        }

        ViewBag.Related = await db.Products.Include(item => item.Category)
            .Where(item => item.CategoryId == product.CategoryId && item.Id != product.Id)
            .Take(4).ToListAsync(cancellationToken);
        return View(product);
    }
}
