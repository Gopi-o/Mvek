namespace UP_Zoo.Models;

public class AdminUsersViewModel
{
    public IReadOnlyList<AppUser> Users { get; init; } = [];
    public IReadOnlyList<string> Roles { get; init; } = [];
}
