using UP_Zoo.Models;

namespace UP_Zoo.Data;

public static class AppDbInitializer
{
    public static async Task InitializeAsync(IServiceProvider services, IConfiguration configuration)
    {
        await using var scope = services.CreateAsyncScope();
        var db = scope.ServiceProvider.GetRequiredService<AppDbContext>();
        await db.Database.MigrateAsync();

        var roleManager = scope.ServiceProvider.GetRequiredService<RoleManager<IdentityRole>>();
        foreach (var role in new[] { ShopRoles.Admin, ShopRoles.Manager, ShopRoles.Customer })
        {
            if (!await roleManager.RoleExistsAsync(role))
            {
                var result = await roleManager.CreateAsync(new IdentityRole(role));
                EnsureSucceeded(result);
            }
        }

        await SeedCatalogAsync(db);

        var adminEmail = configuration["Admin:Email"];
        var adminPassword = configuration["Admin:Password"];
        if (string.IsNullOrWhiteSpace(adminEmail) || string.IsNullOrWhiteSpace(adminPassword))
        {
            return;
        }

        var userManager = scope.ServiceProvider.GetRequiredService<UserManager<AppUser>>();
        var admin = await userManager.FindByEmailAsync(adminEmail);
        if (admin is null)
        {
            admin = new AppUser { UserName = adminEmail, Email = adminEmail, DisplayName = "Администратор" };
            EnsureSucceeded(await userManager.CreateAsync(admin, adminPassword));
        }

        if (!await userManager.IsInRoleAsync(admin, ShopRoles.Admin))
        {
            EnsureSucceeded(await userManager.AddToRoleAsync(admin, ShopRoles.Admin));
        }
    }

    private static async Task SeedCatalogAsync(AppDbContext db)
    {
        if (!await db.Categories.AnyAsync())
        {
            db.Categories.AddRange(
                new Category { Name = "Для собак", Slug = "dogs", Icon = "🐕", Description = "Корм и аксессуары для собак" },
                new Category { Name = "Для кошек", Slug = "cats", Icon = "🐈", Description = "Всё для кошек и котят" },
                new Category { Name = "Для птиц", Slug = "birds", Icon = "🦜", Description = "Товары для пернатых друзей" },
                new Category { Name = "Для грызунов", Slug = "small-pets", Icon = "🐹", Description = "Забота о маленьких питомцах" });
            await db.SaveChangesAsync();
        }

        if (!await db.Brands.AnyAsync())
        {
            db.Brands.AddRange(
                new Brand { Name = "PetJoy", Slug = "petjoy", Description = "Забота каждый день" },
                new Brand { Name = "Happy Tail", Slug = "happy-tail", Description = "Для весёлых прогулок" },
                new Brand { Name = "Purrfect", Slug = "purrfect", Description = "Для мурлыкающего счастья" });
            await db.SaveChangesAsync();
        }

        if (await db.Products.AnyAsync())
        {
            return;
        }

        var categories = await db.Categories.ToDictionaryAsync(category => category.Slug);
        var brands = await db.Brands.ToDictionaryAsync(brand => brand.Slug);
        db.Products.AddRange(
            new Product { Name = "Полнорационный корм с индейкой", Slug = "dog-food-turkey", Category = categories["dogs"], Brand = brands["petjoy"], Description = "Вкусный корм для ежедневного рациона взрослых собак.", Price = 1890, SalePrice = 1590, Stock = 24, IsFeatured = true },
            new Product { Name = "Игрушка-мышка с кошачьей мятой", Slug = "catnip-mouse", Category = categories["cats"], Brand = brands["purrfect"], Description = "Игрушка для охотничьих игр дома.", Price = 490, Stock = 18, IsNew = true, IsFeatured = true },
            new Product { Name = "Миска керамическая «Луна»", Slug = "ceramic-bowl-moon", Category = categories["cats"], Brand = brands["purrfect"], Description = "Устойчивая миска с гладкой поверхностью.", Price = 890, SalePrice = 690, Stock = 12, IsFeatured = true },
            new Product { Name = "Поводок для прогулок, 2 м", Slug = "walking-leash", Category = categories["dogs"], Brand = brands["happy-tail"], Description = "Прочный поводок для ежедневных приключений.", Price = 1290, Stock = 9, IsNew = true },
            new Product { Name = "Зерновая смесь для попугаев", Slug = "parakeet-seed-mix", Category = categories["birds"], Brand = brands["petjoy"], Description = "Сбалансированная смесь для разнообразного питания.", Price = 590, Stock = 32 },
            new Product { Name = "Домик-тоннель для грызунов", Slug = "small-pet-tunnel", Category = categories["small-pets"], Brand = brands["petjoy"], Description = "Уютное место для игр и отдыха питомца.", Price = 790, Stock = 7, IsNew = true });
        await db.SaveChangesAsync();

        db.Reviews.AddRange(
            new Review { AuthorName = "Алина", Rating = 5, Text = "Пёс доволен каждой прогулкой, доставка приехала быстро. Спасибо!", IsApproved = true },
            new Review { AuthorName = "Марк", Rating = 5, Text = "Наш кот сразу оценил новую игрушку. Теперь это его любимая мышка.", IsApproved = true });
        await db.SaveChangesAsync();
    }

    private static void EnsureSucceeded(IdentityResult result)
    {
        if (!result.Succeeded)
        {
            throw new InvalidOperationException(string.Join("; ", result.Errors.Select(error => error.Description)));
        }
    }
}
