@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-circle me-2"></i>Профиль пользователя
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="Аватар" 
                                     class="img-thumbnail rounded-circle" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                     style="width: 150px; height: 150px; margin: 0 auto;">
                                    <i class="fas fa-user fa-4x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 30%">Имя:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Телефон:</th>
                                    <td>{{ $user->phone ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Дата рождения:</th>
                                    <td>{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d.m.Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Пол:</th>
                                    <td>{{ $user->gender ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Username:</th>
                                    <td>{{ $user->username ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Дата регистрации:</th>
                                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">На главную</a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Выйти</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection