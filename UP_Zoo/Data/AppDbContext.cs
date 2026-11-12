using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Models;

namespace UP_Zoo.Data;

public class AppDbContext(DbContextOptions<AppDbContext> options)
    : IdentityDbContext<AppUser, IdentityRole, string>(options)
{
    public DbSet<Product> Products => Set<Product>();
    public DbSet<Category> Categories => Set<Category>();
    public DbSet<Brand> Brands => Set<Brand>();
    public DbSet<ShopOrder> ShopOrders => Set<ShopOrder>();
    public DbSet<OrderItem> OrderItems => Set<OrderItem>();
    public DbSet<Review> Reviews => Set<Review>();
    public DbSet<ContactMessage> ContactMessages => Set<ContactMessage>();

    protected override void OnModelCreating(ModelBuilder builder)
    {
        base.OnModelCreating(builder);

        builder.Entity<Product>().HasIndex(product => product.Slug).IsUnique();
        builder.Entity<Category>().HasIndex(category => category.Slug).IsUnique();
        builder.Entity<Brand>().HasIndex(brand => brand.Slug).IsUnique();
        builder.Entity<ShopOrder>().HasIndex(order => order.Status);
        builder.Entity<Review>().HasIndex(review => review.IsApproved);

        builder.Entity<Product>()
            .HasOne(product => product.Category)
            .WithMany(category => category.Products)
            .HasForeignKey(product => product.CategoryId)
            .OnDelete(DeleteBehavior.SetNull);

        builder.Entity<Product>()
            .HasOne(product => product.Brand)
            .WithMany(brand => brand.Products)
            .HasForeignKey(product => product.BrandId)
            .OnDelete(DeleteBehavior.SetNull);

        builder.Entity<ShopOrder>()
            .HasOne(order => order.User)
            .WithMany(user => user.Orders)
            .HasForeignKey(order => order.UserId)
            .OnDelete(DeleteBehavior.SetNull);

        builder.Entity<OrderItem>()
            .HasOne(item => item.Order)
            .WithMany(order => order.Items)
            .HasForeignKey(item => item.ShopOrderId)
            .OnDelete(DeleteBehavior.Cascade);

        builder.Entity<OrderItem>()
            .HasOne(item => item.Product)
            .WithMany(product => product.OrderItems)
            .HasForeignKey(item => item.ProductId)
            .OnDelete(DeleteBehavior.SetNull);

        builder.Entity<Review>()
            .HasOne(review => review.User)
            .WithMany()
            .HasForeignKey(review => review.UserId)
            .OnDelete(DeleteBehavior.SetNull);
    }
}
