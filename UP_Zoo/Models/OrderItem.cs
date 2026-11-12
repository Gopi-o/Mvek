using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace UP_Zoo.Models;

public class OrderItem
{
    public int Id { get; set; }

    public int ShopOrderId { get; set; }

    public ShopOrder Order { get; set; } = null!;

    public int? ProductId { get; set; }

    public Product? Product { get; set; }

    [Required, StringLength(180)]
    public string ProductName { get; set; } = string.Empty;

    [Column(TypeName = "decimal(10,2)")]
    public decimal Price { get; set; }

    [Range(1, 999)]
    public int Quantity { get; set; }
}
