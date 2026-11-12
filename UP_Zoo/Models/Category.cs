using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class Category
{
    public int Id { get; set; }

    [Required, StringLength(120)]
    public string Name { get; set; } = string.Empty;

    [Required, StringLength(140)]
    public string Slug { get; set; } = string.Empty;

    [StringLength(20)]
    public string? Icon { get; set; }

    [StringLength(1000)]
    public string? Description { get; set; }

    public ICollection<Product> Products { get; set; } = new List<Product>();
}
