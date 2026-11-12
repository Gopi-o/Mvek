using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using UP_Zoo.Data;
using UP_Zoo.Models;

namespace UP_Zoo.Controllers;

[Route("account")]
public class AccountController(
    UserManager<AppUser> userManager,
    SignInManager<AppUser> signInManager,
    AppDbContext db) : Controller
{
    [HttpGet("")]
    [Authorize]
    public async Task<IActionResult> Index(CancellationToken cancellationToken)
    {
        var userId = User.FindFirstValue(ClaimTypes.NameIdentifier)!;
        var orders = await db.ShopOrders.Include(order => order.Items)
            .Where(order => order.UserId == userId)
            .OrderByDescending(order => order.CreatedAt)
            .ToListAsync(cancellationToken);
        return View(orders);
    }

    [HttpGet("register")]
    [AllowAnonymous]
    public IActionResult Register() => View(new RegisterViewModel());

    [HttpPost("register")]
    [AllowAnonymous]
    public async Task<IActionResult> Register(RegisterViewModel model)
    {
        if (!ModelState.IsValid)
        {
            return View(model);
        }

        var user = new AppUser
        {
            UserName = model.Email,
            Email = model.Email,
            DisplayName = model.DisplayName,
            CreatedAt = DateTime.UtcNow
        };
        var result = await userManager.CreateAsync(user, model.Password);
        if (result.Succeeded)
        {
            var roleResult = await userManager.AddToRoleAsync(user, ShopRoles.Customer);
            if (!roleResult.Succeeded)
            {
                throw new InvalidOperationException(string.Join("; ", roleResult.Errors.Select(error => error.Description)));
            }
            await signInManager.SignInAsync(user, isPersistent: false);
            return RedirectToAction(nameof(Index));
        }

        AddErrors(result);
        return View(model);
    }

    [HttpGet("login")]
    [AllowAnonymous]
    public IActionResult Login(string? returnUrl = null)
    {
        ViewBag.ReturnUrl = returnUrl;
        return View(new LoginViewModel());
    }

    [HttpPost("login")]
    [AllowAnonymous]
    public async Task<IActionResult> Login(LoginViewModel model, string? returnUrl = null)
    {
        ViewBag.ReturnUrl = returnUrl;
        if (!ModelState.IsValid)
        {
            return View(model);
        }

        var result = await signInManager.PasswordSignInAsync(
            model.Email, model.Password, model.RememberMe, lockoutOnFailure: true);
        if (result.Succeeded)
        {
            return LocalRedirect(returnUrl ?? Url.Action(nameof(Index))!);
        }

        ModelState.AddModelError(string.Empty, result.IsLockedOut
            ? "Учётная запись временно заблокирована. Попробуйте позже."
            : "Неверная почта или пароль.");
        return View(model);
    }

    [HttpPost("logout")]
    [Authorize]
    public async Task<IActionResult> Logout()
    {
        await signInManager.SignOutAsync();
        return RedirectToAction("Index", "Home");
    }

    [HttpGet("access-denied")]
    public IActionResult AccessDenied() => View();

    private void AddErrors(IdentityResult result)
    {
        foreach (var error in result.Errors)
        {
            var message = error.Code switch
            {
                "DuplicateEmail" or "DuplicateUserName" => "Аккаунт с такой электронной почтой уже существует.",
                "InvalidEmail" => "Введите корректный адрес электронной почты.",
                "PasswordTooShort" => "Пароль слишком короткий. Используйте не менее 8 символов.",
                "PasswordRequiresDigit" => "Пароль должен содержать хотя бы одну цифру.",
                "PasswordRequiresLower" => "Пароль должен содержать хотя бы одну строчную букву.",
                "PasswordRequiresUpper" => "Пароль должен содержать хотя бы одну заглавную букву.",
                "PasswordRequiresNonAlphanumeric" => "Пароль должен содержать хотя бы один специальный символ.",
                "PasswordRequiresUniqueChars" => "Пароль содержит недостаточно разных символов.",
                "InvalidUserName" => "Имя пользователя имеет недопустимый формат.",
                _ => error.Description
            };
            ModelState.AddModelError(string.Empty, message);
        }
    }
}
