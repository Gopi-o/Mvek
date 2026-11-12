using System.Diagnostics;
using Microsoft.EntityFrameworkCore;
using Microsoft.AspNetCore.Mvc;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Controllers;

public class HomeController : Controller
{
    private readonly AppDbContext _db;

    public HomeController(AppDbContext db)
    {
        _db = db;
    }

    public async Task<IActionResult> Index(CancellationToken cancellationToken)
    {
        var model = new HomeViewModel
        {
            Categories = await _db.Categories.Include(category => category.Products)
                .OrderBy(category => category.Name).ToListAsync(cancellationToken),
            Products = await _db.Products.Include(product => product.Category).Include(product => product.Brand)
                .Where(product => product.IsFeatured).Take(4).ToListAsync(cancellationToken),
            Reviews = await _db.Reviews.Where(review => review.IsApproved).OrderByDescending(review => review.CreatedAt)
                .Take(3).ToListAsync(cancellationToken)
        };

        return View(model);
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
