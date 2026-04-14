from django.shortcuts import render, redirect, get_object_or_404
from django.views.generic import ListView, DetailView, CreateView, UpdateView, DeleteView, TemplateView
from django.contrib.auth.views import LoginView, LogoutView
from django.contrib.auth import login
from django.contrib.auth.mixins import LoginRequiredMixin
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.urls import reverse_lazy, reverse
from django.utils.text import slugify
import secrets
import string

from .models import Project, UserProfile, Document, ConfigFile, LogEntry
from .forms import ProjectForm, UserRegisterForm, ProfileForm, DocumentForm



class LandingView(TemplateView):
    template_name = 'bot_app/landing/index.html'
    
    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context['features'] = [
            {
                'icon': '🤖',
                'title': 'Telegram Bot',
                'desc': 'Управляй проектами прямо из Telegram. Быстро, удобно, без отвлечений.'
            },
            {
                'icon': '📚',
                'title': 'Документация',
                'desc': 'Редактируй README, CHANGELOG и другие документы без захода в Git.'
            },
            {
                'icon': '⚙️',
                'title': 'Конфигурации',
                'desc': 'Управляй .env файлами и JSON-конфигами через веб-интерфейс или бота.'
            },
            {
                'icon': '📊',
                'title': 'Логи',
                'desc': 'Просматривай, фильтруй и скачивай логи проектов в реальном времени.'
            },
            {
                'icon': '🔗',
                'title': 'Git Integration',
                'desc': 'Автоматические коммиты в специальную ветку. Никаких конфликтов.'
            },
            {
                'icon': '🔒',
                'title': 'Безопасность',
                'desc': 'Токены и SSH-ключи хранятся безопасно. Двухфакторная аутентификация.'
            }
        ]
        return context
    

class FeaturesView(TemplateView):
    template_name = 'bot_app/landing/features.html'


class RegisterView(CreateView):
    form_class = UserRegisterForm
    template_name = 'bot_app/accounts/register.html'
    success_url = reverse_lazy('project_list')
    
    def form_valid(self, form):
        response = super().form_valid(form)
        UserProfile.objects.create(user=self.object)
        login(self.request, self.object)
        messages.success(self.request, 'Добро пожаловать! Ваш аккаунт создан.')
        return response
    

class CustomLoginView(LoginView):
    template_name = 'bot_app/accounts/login.html'
    redirect_authenticated_user = True
    
    def get_success_url(self):
        return reverse_lazy('project_list')
    
class CustomLogoutView(LogoutView):
    next_page = 'landing'


@login_required
def profile_view(request):
    profile, created = UserProfile.objects.get_or_create(user=request.user)
    
    if request.method == 'POST':
        form = ProfileForm(request.POST, request.FILES, instance=profile)
        if form.is_valid():
            form.save()
            messages.success(request, 'Профиль обновлён!')
            return redirect('profile')
    else:
        form = ProfileForm(instance=profile)
    
    projects_count = Project.objects.filter(user=request.user).count()
    active_projects = Project.objects.filter(user=request.user, is_active=True).count()
    
    return render(request, 'bot_app/accounts/profile.html', {
        'form': form,
        'profile': profile,
        'projects_count': projects_count,
        'active_projects': active_projects
    })

@login_required
def delete_account_view(request):
    if request.method == 'POST':
        user = request.user
        user.projects.all().delete()
        if hasattr(user, 'profile'):
            user.profile.delete()
        user.delete()
        messages.success(request, 'Ваш аккаунт и все данные удалены.')
        return redirect('landing')
    
    return render(request, 'bot_app/accounts/delete_confirm.html')

@login_required
def clear_bot_data_view(request):
    if request.method == 'POST':
        request.user.projects.all().delete()
        if hasattr(request.user, 'profile'):
            request.user.profile.telegram_user_id = None
            request.user.profile.save()
        messages.success(request, 'Все данные бота очищены. Проекты удалены.')
        return redirect('profile')
    
    return render(request, 'bot_app/accounts/clear_data_confirm.html')


class ProjectListView(LoginRequiredMixin, ListView):
    model = Project
    template_name = 'bot_app/dashboard/project_list.html'
    context_object_name = 'projects'
    
    def get_queryset(self):
        return Project.objects.filter(user=self.request.user)
    
    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context['total_projects'] = self.get_queryset().count()
        context['active_projects'] = self.get_queryset().filter(is_active=True).count()
        return context


class ProjectDetailView(LoginRequiredMixin, DetailView):
    model = Project
    template_name = 'bot_app/dashboard/project_detail.html'
    context_object_name = 'project'
    slug_url_kwarg = 'slug'
    
    def get_queryset(self):
        return Project.objects.filter(user=self.request.user)
    
    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context['bot_command'] = self.object.get_bot_command()
        context['recent_docs'] = self.object.documents.all()[:5]
        context['recent_logs'] = self.object.logs.all()[:10]
        return context


class ProjectCreateView(LoginRequiredMixin, CreateView):
    model = Project
    form_class = ProjectForm
    template_name = 'bot_app/dashboard/project_form.html'
    
    def form_valid(self, form):
        project = form.save(commit=False)
        project.user = self.request.user
        
        base_slug = slugify(project.name)[:50]
        slug = base_slug
        counter = 1
        while Project.objects.filter(slug=slug).exists():
            slug = f"{base_slug}-{counter}"
            counter += 1
        project.slug = slug
        
        if hasattr(self.request.user, 'profile'):
            project.telegram_user_id = self.request.user.profile.telegram_user_id
        
        project.save()
        messages.success(self.request, f'Проект "{project.name}" создан!')
        return redirect('project_detail', slug=project.slug)


class ProjectUpdateView(LoginRequiredMixin, UpdateView):
    model = Project
    form_class = ProjectForm
    template_name = 'bot_app/dashboard/project_form.html'
    slug_url_kwarg = 'slug'
    
    def get_queryset(self):
        return Project.objects.filter(user=self.request.user)
    
    def get_success_url(self):
        messages.success(self.request, 'Проект обновлён!')
        return reverse('project_detail', kwargs={'slug': self.object.slug})


class ProjectDeleteView(LoginRequiredMixin, DeleteView):
    model = Project
    template_name = 'bot_app/dashboard/project_confirm_delete.html'
    slug_url_kwarg = 'slug'
    success_url = reverse_lazy('project_list')
    
    def get_queryset(self):
        return Project.objects.filter(user=self.request.user)
    
    def delete(self, request, *args, **kwargs):
        messages.success(request, 'Проект удалён!')
        return super().delete(request, *args, **kwargs)
    

@login_required
def files_manager_view(request, slug):
    """Управление документацией проекта"""
    project = get_object_or_404(Project, slug=slug, user=request.user)
    documents = project.documents.all()
    
    return render(request, 'bot_app/dashboard/files_manager.html', {
        'project': project,
        'documents': documents
    })


@login_required
def document_edit_view(request, slug, doc_id=None):
    """Создание/редактирование документа"""
    project = get_object_or_404(Project, slug=slug, user=request.user)
    
    if doc_id:
        document = get_object_or_404(Document, id=doc_id, project=project)
    else:
        document = None
    
    if request.method == 'POST':
        form = DocumentForm(request.POST, instance=document)
        if form.is_valid():
            doc = form.save(commit=False)
            doc.project = project
            doc.save()
            messages.success(request, 'Документ сохранён!')
            return redirect('files_manager', slug=slug)
    else:
        form = DocumentForm(instance=document)
    
    return render(request, 'bot_app/dashboard/document_edit.html', {
        'form': form,
        'project': project,
        'document': document
    })


@login_required
def config_manager_view(request, slug):
    """Управление конфигами проекта"""
    project = get_object_or_404(Project, slug=slug, user=request.user)
    configs = project.configs.all()
    
    return render(request, 'bot_app/dashboard/config_manager.html', {
        'project': project,
        'configs': configs,
        'env_vars': project.env_variables
    })


@login_required
def logs_viewer_view(request, slug):
    """Просмотр логов проекта"""
    project = get_object_or_404(Project, slug=slug, user=request.user)
    logs = project.logs.all()
    
    level_filter = request.GET.get('level')
    if level_filter:
        logs = logs.filter(level=level_filter)
    
    search = request.GET.get('search')
    if search:
        logs = logs.filter(message__icontains=search)
    
    return render(request, 'bot_app/dashboard/logs_viewer.html', {
        'project': project,
        'logs': logs[:100], 
        'level_filter': level_filter,
        'search': search
    })