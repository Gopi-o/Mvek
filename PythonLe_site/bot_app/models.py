from django.db import models
from django.contrib.auth.models import User
from django.urls import reverse
import secrets
import string


# Генерирует уникальное сокращение для проекта.
def generate_slug():
    return ''.join(secrets.choice(string.ascii_lowercase + string.digits) for _ in range(8))


class UserProfile(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='profile')
    telegram_user_id = models.BigIntegerField(null=True, blank=True, unique=True)
    telegram_username = models.CharField(max_length=100, blank=True)
    bio = models.TextField(blank=True, max_length=500)
    avatar = models.ImageField(upload_to='avatars/', blank=True)
    
    # Cинхронизация
    auto_sync = models.BooleanField(default=True, verbose_name="Авто-синхронизация с ботом")
    notify_commits = models.BooleanField(default=True, verbose_name="Уведомлять о коммитах")
    
    created_at = models.DateTimeField(auto_now_add=True)
    
    def __str__(self):
        return f"Profile of {self.user.username}"
    
    def delete_all_data(self):
        self.user.projects.all().delete()
        self.telegram_user_id = None
        self.telegram_username = ""
        self.bio = ""
        self.save()

class Project(models.Model):
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name='projects')
    telegram_user_id = models.BigIntegerField(null=True, blank=True)
    
    name = models.CharField(max_length=100, verbose_name="Название проекта")
    slug = models.SlugField(unique=True, default=generate_slug)
    project_type = models.CharField(max_length=50, choices=[
        ('neuro_chat', 'Neuro-Chat Bot'),
        ('webapp', 'Web App Boilerplate'),
        ('data_api', 'Data Analytics API'),
        ('custom', 'Custom Project'),
    ], default='custom', verbose_name="Тип проекта")
    description = models.TextField(blank=True, verbose_name="Описание")
    
    # Git-интеграция
    repo_url = models.URLField(verbose_name="URL репозитория")
    branch = models.CharField(max_length=100, default='bot/docs-updates', verbose_name="Ветка")
    git_token = models.CharField(max_length=255, blank=True, verbose_name="Git PAT")
    use_ssh = models.BooleanField(default=False, verbose_name="Использовать SSH")
    
    # Пути в репозитории
    logs_path = models.CharField(max_length=200, default='bot/logs', verbose_name="Путь к логам")
    docs_path = models.CharField(max_length=200, default='bot/docs', verbose_name="Путь к докам")
    config_path = models.CharField(max_length=200, default='bot/config', verbose_name="Путь к конфигам")
    
    # Статус
    is_active = models.BooleanField(default=True, verbose_name="Активен")
    last_sync = models.DateTimeField(null=True, blank=True, verbose_name="Последняя синхронизация")
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    # Данные для бота (JSON)
    documents_meta = models.JSONField(default=dict, blank=True)
    env_variables = models.JSONField(default=dict, blank=True)
    
    class Meta:
        ordering = ['-updated_at']
        verbose_name = "Проект"
        verbose_name_plural = "Проекты"
        unique_together = ['user', 'name']
    
    def __str__(self):
        return self.name
    
    def get_absolute_url(self):
        return reverse('project_detail', kwargs={'slug': self.slug})
    
    def get_bot_command(self):
        return f"/Edit_project[{self.id}]"


class Document(models.Model):
    project = models.ForeignKey(Project, on_delete=models.CASCADE, related_name='documents')
    filename = models.CharField(max_length=255, verbose_name="Имя файла")
    content = models.TextField(verbose_name="Содержимое", blank=True)
    last_commit_sha = models.CharField(max_length=40, blank=True)
    updated_at = models.DateTimeField(auto_now=True)
    
    class Meta:
        unique_together = ['project', 'filename']
        verbose_name = "Документ"
        verbose_name_plural = "Документы"
    
    def __str__(self):
        return f"{self.project.name}/{self.filename}"


class ConfigFile(models.Model):
    project = models.ForeignKey(Project, on_delete=models.CASCADE, related_name='configs')
    filename = models.CharField(max_length=255, verbose_name="Имя файла")
    content = models.TextField(verbose_name="Содержимое", blank=True)
    is_env = models.BooleanField(default=False, verbose_name="Это .env файл?")
    
    class Meta:
        unique_together = ['project', 'filename']
        verbose_name = "Конфиг"
        verbose_name_plural = "Конфиги"


class LogEntry(models.Model):
    """Логи проекта"""
    LEVEL_CHOICES = [
        ('DEBUG', 'DEBUG'),
        ('INFO', 'INFO'),
        ('WARNING', 'WARNING'),
        ('ERROR', 'ERROR'),
        ('CRITICAL', 'CRITICAL'),
    ]
    
    project = models.ForeignKey(Project, on_delete=models.CASCADE, related_name='logs')
    level = models.CharField(max_length=20, choices=LEVEL_CHOICES, default='INFO')
    message = models.TextField()
    source_file = models.CharField(max_length=255, blank=True)
    timestamp = models.DateTimeField(auto_now_add=True)
    
    class Meta:
        ordering = ['-timestamp']
        verbose_name = "Лог"
        verbose_name_plural = "Логи"