from django.urls import path
from . import views

urlpatterns = [
    path('', views.LandingView.as_view(), name='landing'),
    path('features/', views.FeaturesView.as_view(), name='features'),
    
    path('auth/register/', views.RegisterView.as_view(), name='register'),
    path('auth/login/', views.CustomLoginView.as_view(), name='login'),
    path('auth/logout/', views.CustomLogoutView.as_view(), name='logout'),
    path('auth/profile/', views.profile_view, name='profile'),
    path('auth/delete-account/', views.delete_account_view, name='delete_account'),
    path('auth/clear-data/', views.clear_bot_data_view, name='clear_bot_data'),
    
    path('projects/', views.ProjectListView.as_view(), name='project_list'),
    path('projects/create/', views.ProjectCreateView.as_view(), name='project_create'),
    path('projects/<slug:slug>/', views.ProjectDetailView.as_view(), name='project_detail'),
    path('projects/<slug:slug>/edit/', views.ProjectUpdateView.as_view(), name='project_update'),
    path('projects/<slug:slug>/delete/', views.ProjectDeleteView.as_view(), name='project_delete'),
    
    path('projects/<slug:slug>/files/', views.files_manager_view, name='files_manager'),
    path('projects/<slug:slug>/files/edit/', views.document_edit_view, name='document_create'),
    path('projects/<slug:slug>/files/edit/<int:doc_id>/', views.document_edit_view, name='document_edit'),
    path('projects/<slug:slug>/configs/', views.config_manager_view, name='config_manager'),
    path('projects/<slug:slug>/logs/', views.logs_viewer_view, name='logs_viewer'),
]