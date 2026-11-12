using Microsoft.AspNetCore.Identity;

namespace UP_Zoo.Models;

public class AppUser : IdentityUser
{
    public string DisplayName { get; set; } = string.Empty;

    public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

    public ICollection<ShopOrder> Orders { get; set; } = new List<ShopOrder>();
}
