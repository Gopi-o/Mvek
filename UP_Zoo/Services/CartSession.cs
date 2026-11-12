using System.Text.Json;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Services;

public static class CartSession
{
    private const string SessionKey = "upzoo.cart";

    public static Dictionary<int, int> GetQuantities(ISession session)
    {
        var json = session.GetString(SessionKey);
        return string.IsNullOrEmpty(json)
            ? []
            : JsonSerializer.Deserialize<Dictionary<int, int>>(json) ?? [];
    }

    public static void SaveQuantities(ISession session, Dictionary<int, int> quantities)
    {
        if (quantities.Count == 0)
        {
            session.Remove(SessionKey);
            return;
        }

        session.SetString(SessionKey, JsonSerializer.Serialize(quantities));
    }

    public static async Task<IReadOnlyList<CartItem>> LoadAsync(
        ISession session,
        AppDbContext db,
        CancellationToken cancellationToken = default)
    {
        var quantities = GetQuantities(session);
        if (quantities.Count == 0)
        {
            return [];
        }

        var products = await db.Products
            .Include(product => product.Category)
            .Where(product => quantities.Keys.Contains(product.Id))
            .ToListAsync(cancellationToken);

        return products.Select(product => new CartItem(
            product.Id,
            product.Name,
            product.CurrentPrice,
            product.ImageUrl,
            product.Category?.Icon,
            quantities[product.Id])).ToList();
    }
}
