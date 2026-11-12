using System.ComponentModel.DataAnnotations;

namespace UP_Zoo.Models;

public class LoginViewModel
{
    [Required(ErrorMessage = "Укажите электронную почту.")]
    [EmailAddress(ErrorMessage = "Введите корректный адрес электронной почты.")]
    public string Email { get; set; } = string.Empty;

    [Required(ErrorMessage = "Введите пароль.")]
    [DataType(DataType.Password)]
    public string Password { get; set; } = string.Empty;

    public bool RememberMe { get; set; }
}
