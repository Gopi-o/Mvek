using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class RegisterViewModel
{
    [Required(ErrorMessage = "Укажите имя."), StringLength(120, ErrorMessage = "Имя не должно превышать 120 символов.")]
    public string DisplayName { get; set; } = string.Empty;

    [Required(ErrorMessage = "Укажите электронную почту.")]
    [EmailAddress(ErrorMessage = "Введите корректный адрес электронной почты.")]
    [StringLength(254, ErrorMessage = "Адрес электронной почты слишком длинный.")]
    public string Email { get; set; } = string.Empty;

    [Required(ErrorMessage = "Укажите пароль.")]
    [DataType(DataType.Password)]
    [MinLength(8, ErrorMessage = "Пароль должен содержать не менее 8 символов.")]
    public string Password { get; set; } = string.Empty;

    [Required(ErrorMessage = "Повторите пароль.")]
    [DataType(DataType.Password)]
    [Compare(nameof(Password), ErrorMessage = "Пароли не совпадают.")]
    public string ConfirmPassword { get; set; } = string.Empty;
}
