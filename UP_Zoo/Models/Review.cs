using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class Review
{
    public int Id { get; set; }

    public string? UserId { get; set; }

    public AppUser? User { get; set; }

    public int? ProductId { get; set; }

    public Product? Product { get; set; }

    [Required, StringLength(120)]
    public string AuthorName { get; set; } = string.Empty;

    [Range(1, 5)]
    public int Rating { get; set; } = 5;

    [Required, StringLength(1000, MinimumLength = 10)]
    public string Text { get; set; } = string.Empty;

    public bool IsApproved { get; set; }

    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
}
