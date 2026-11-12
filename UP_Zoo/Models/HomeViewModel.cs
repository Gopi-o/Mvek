namespace UP_Zoo.Models;

public class HomeViewModel
{
    public IReadOnlyList<Category> Categories { get; init; } = [];
    public IReadOnlyList<Product> Products { get; init; } = [];
    public IReadOnlyList<Review> Reviews { get; init; } = [];
}
