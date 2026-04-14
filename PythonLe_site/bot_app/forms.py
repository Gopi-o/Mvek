from django import forms
from django.contrib.auth.forms import UserCreationForm
from django.contrib.auth.models import User
from .models import Project, UserProfile, Document

# Форма создания или редактирования проекта
class ProjectForm(forms.ModelForm):
    class Meta:
        model = Project
        fields = [
            'name', 'project_type', 'description',
            'repo_url', 'branch', 'git_token', 'use_ssh',
            'logs_path', 'docs_path', 'config_path',
            'is_active'
        ]
        widgets = {
            'name': forms.TextInput(attrs={
                'class': 'form-control',
                'placeholder': 'Например: Neuro-Chat Bot'
            }),
            'project_type': forms.Select(attrs={'class': 'form-control'}),
            'description': forms.Textarea(attrs={
                'class': 'form-control',
                'rows': 3,
                'placeholder': 'Краткое описание проекта...'
            }),
            'repo_url': forms.URLInput(attrs={
                'class': 'form-control',
                'placeholder': 'https://github.com/username/repo'
            }),
            'branch': forms.TextInput(attrs={
                'class': 'form-control',
                'placeholder': 'bot/docs-updates'
            }),
            'git_token': forms.PasswordInput(attrs={
                'class': 'form-control',
                'placeholder': 'ghp_xxxxxxxxxxxx'
            }),
            'use_ssh': forms.CheckboxInput(attrs={'class': 'form-check-input'}),
            'logs_path': forms.TextInput(attrs={'class': 'form-control'}),
            'docs_path': forms.TextInput(attrs={'class': 'form-control'}),
            'config_path': forms.TextInput(attrs={'class': 'form-control'}),
            'is_active': forms.CheckboxInput(attrs={'class': 'form-check-input'}),
        }

# Форма регистрации пользователя
class UserRegisterForm(UserCreationForm):
    email = forms.EmailField(required=True)
    
    class Meta:
        model = User
        fields = ['username', 'email', 'password1', 'password2']
    
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        for field in self.fields.values():
            field.widget.attrs['class'] = 'form-control'

# Форма редактирования документа
class DocumentForm(forms.ModelForm):
    class Meta:
        model = Document
        fields = ['filename', 'content']
        widgets = {
            'filename': forms.TextInput(attrs={'class': 'form-control'}),
            'content': forms.Textarea(attrs={
                'class': 'form-control',
                'rows': 20,
                'style': 'font-family: monospace;'
            }),
        }

# Форма профиля
class ProfileForm(forms.ModelForm):
    class Meta:
        model = UserProfile
        fields = ['bio', 'telegram_username', 'auto_sync', 'notify_commits']
        widgets = {
            'bio': forms.Textarea(attrs={'class': 'form-control', 'rows': 3}),
            'telegram_username': forms.TextInput(attrs={'class': 'form-control'}),
            'auto_sync': forms.CheckboxInput(attrs={'class': 'form-check-input'}),
            'notify_commits': forms.CheckboxInput(attrs={'class': 'form-check-input'}),
        }