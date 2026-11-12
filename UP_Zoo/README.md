# UP_ZOO — ASP.NET Core 10

Учебный интернет-магазин товаров для животных на ASP.NET Core MVC (.NET 10), Razor, ASP.NET Core Identity и Microsoft SQL Server.

## Требования

- .NET SDK 10
- Microsoft SQL Server (Database Engine) и SQL Server Management Studio (SSMS)
- Отдельная пустая база `up_zoo_aspnet`

SSMS — это клиент для управления базами данных; для подключения приложения должен быть установлен и запущен сам Microsoft SQL Server Database Engine. Laravel-проект и его база данных не используются этой версией.

## Настройка

В SSMS подключитесь к экземпляру SQL Server и создайте отдельную базу:

```sql
CREATE DATABASE up_zoo_aspnet;
```

В проекте уже настроен User Secrets. Задайте строку подключения из каталога проекта. Для стандартного локального экземпляра SQL Server используйте:

```powershell
dotnet user-secrets set "ConnectionStrings:DefaultConnection" "Server=localhost;Database=up_zoo_aspnet;Integrated Security=True;TrustServerCertificate=True"
dotnet user-secrets set "Admin:Email" "admin@example.com"
dotnet user-secrets set "Admin:Password" "СЛОЖНЫЙ_ПАРОЛЬ"
```

Если у вас именованный экземпляр, замените `localhost` на имя SQL Server из окна подключения SSMS, например `localhost\SQLEXPRESS`. При SQL Server Authentication используйте `User ID` и `Password` вместо `Integrated Security=True`. Выполняйте команду именно из каталога `UP_Zoo`.

ASP.NET Core не загружает `.env` автоматически. В качестве альтернативы задайте переменные окружения `ConnectionStrings__DefaultConnection`, `Admin__Email` и `Admin__Password`. Не помещайте реальные пароли в отслеживаемые файлы настроек или Git.

В режиме разработки приложение применяет EF Core-миграции, создаёт роли, начальные категории, бренды и товары. Если переменные `Admin:Email` и `Admin:Password` заданы, создаётся администратор. Для production миграции нужно применять отдельным шагом; по умолчанию автоматическое применение миграций включено только в Development. Управлять этим можно параметром `Database:ApplyMigrationsOnStartup`.

## Запуск

Из каталога `UP_Zoo`:

```powershell
dotnet restore
dotnet run
```

## Функции

- Главная страница, каталог, поиск и фильтрация, товары, акции, новинки и бренды.
- Сессионная корзина, гостевое и авторизованное оформление заказа.
- Регистрация и вход на ASP.NET Core Identity, личный кабинет и история заказов.
- Форма обратной связи и отзывы после покупки.
- Панель `/admin`: каталог и пользователи доступны администраторам; менеджеры могут обрабатывать заказы и просматривать отзывы.

## Проверки

```powershell
dotnet build
```
