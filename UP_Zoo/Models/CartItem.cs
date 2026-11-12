namespace UP_Zoo.Models;

public record CartItem(int ProductId, string Name, decimal Price, string? ImageUrl, string? CategoryIcon, int Quantity)
{
    public decimal Subtotal => Price * Quantity;
}
