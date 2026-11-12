using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace UP_Zoo.Models;

public class ShopOrder
{
    public int Id { get; set; }

    public string? UserId { get; set; }

    public AppUser? User { get; set; }

    [Required, StringLength(120)]
    public string CustomerName { get; set; } = string.Empty;

    [Required, EmailAddress, StringLength(254)]
    public string Email { get; set; } = string.Empty;

    [Required, StringLength(30)]
    public string Phone { get; set; } = string.Empty;

    [Required, StringLength(255)]
    public string Address { get; set; } = string.Empty;

    [StringLength(1000)]
    public string? Notes { get; set; }

    [Column(TypeName = "decimal(10,2)")]
    public decimal Total { get; set; }

    [Required, StringLength(30)]
    public string Status { get; set; } = OrderStatuses.New;

    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    public ICollection<OrderItem> Items { get; set; } = new List<OrderItem>();
}

public static class OrderStatuses
{
    public const string New = "new";
    public const string Processing = "processing";
    public const string Shipped = "shipped";
    public const string Completed = "completed";
    public const string Cancelled = "cancelled";

    public static readonly IReadOnlyDictionary<string, string> Labels = new Dictionary<string, string>
    {
        [New] = "Новый",
        [Processing] = "В обработке",
        [Shipped] = "Передан в доставку",
        [Completed] = "Выполнен",
        [Cancelled] = "Отменён"
    };
}
