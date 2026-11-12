using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class CheckoutViewModel
{
    [Required, StringLength(120)]
    public string CustomerName { get; set; } = string.Empty;

    [Required, EmailAddress, StringLength(254)]
    public string Email { get; set; } = string.Empty;

    [Required, Phone, StringLength(30)]
    public string Phone { get; set; } = string.Empty;

    [Required, StringLength(255)]
    public string Address { get; set; } = string.Empty;

    [StringLength(1000)]
    public string? Notes { get; set; }
}
