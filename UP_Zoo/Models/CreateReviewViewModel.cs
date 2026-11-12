using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class CreateReviewViewModel
{
    [Required]
    public int? ProductId { get; set; }

    [Range(1, 5)]
    public int Rating { get; set; } = 5;

    [Required, StringLength(1000, MinimumLength = 10)]
    public string Text { get; set; } = string.Empty;
}
