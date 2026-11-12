namespace UP_Zoo.Models;

public class CatalogViewModel
{
    public IReadOnlyList<Product> Products { get; init; } = [];
    public IReadOnlyList<Category> Categories { get; init; } = [];
    public IReadOnlyList<Brand> Brands { get; init; } = [];
    public string? Query { get; init; }
    public string? Category { get; init; }
    public string? Brand { get; init; }
    public decimal? MinPrice { get; init; }
    public decimal? MaxPrice { get; init; }
    public string Title { get; init; } = "Каталог";
}
