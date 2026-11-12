using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace UP_Zoo.Models;

public class Product
{
    public int Id { get; set; }

    public int? CategoryId { get; set; }

    public Category? Category { get; set; }

    public int? BrandId { get; set; }

    public Brand? Brand { get; set; }

    [Required, StringLength(180)]
    public string Name { get; set; } = string.Empty;

    [Required, StringLength(200)]
    public string Slug { get; set; } = string.Empty;

    [StringLength(5000)]
    public string? Description { get; set; }

    [Range(0, 99999999)]
    [Column(TypeName = "decimal(10,2)")]
    public decimal Price { get; set; }

    [Range(0, 99999999)]
    [Column(TypeName = "decimal(10,2)")]
    public decimal? SalePrice { get; set; }

    [Range(0, 1000000)]
    public int Stock { get; set; }

    [Url, StringLength(2048)]
    public string? ImageUrl { get; set; }

    public bool IsNew { get; set; }

    public bool IsFeatured { get; set; }

    public ICollection<OrderItem> OrderItems { get; set; } = new List<OrderItem>();

    public ICollection<Review> Reviews { get; set; } = new List<Review>();

    [NotMapped]
    public decimal CurrentPrice => SalePrice ?? Price;
}
