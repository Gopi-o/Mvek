@extends('layouts.app')

@section('title', 'Регистрация')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-user-plus me-2 text-success"></i>
                    Регистрация
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Имя --}}
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Телефон (международный формат)</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder="+79161234567" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Например: +79161234567</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Дата рождения</label>
                            <input type="text" name="birth_date" id="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                                   value="{{ old('birth_date') }}" placeholder="ДД.ММ.ГГГГ или ГГГГ-ММ-ДД" required>
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Форматы: 31.12.2000, 2000-12-31</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пол</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                <option value="">Выберите...</option>
                                <optgroup label="Для лиц 18 лет и старше">
                                    <option value="М" {{ old('gender') == 'М' ? 'selected' : '' }}>М</option>
                                    <option value="Ж" {{ old('gender') == 'Ж' ? 'selected' : '' }}>Ж</option>
                                    <option value="Мужчина" {{ old('gender') == 'Мужчина' ? 'selected' : '' }}>Мужчина</option>
                                    <option value="Женщина" {{ old('gender') == 'Женщина' ? 'selected' : '' }}>Женщина</option>
                                </optgroup>
                                <optgroup label="Для лиц младше 18 лет">
                                    <option value="мальчик" {{ old('gender') == 'мальчик' ? 'selected' : '' }}>Мальчик</option>
                                    <option value="девочка" {{ old('gender') == 'девочка' ? 'selected' : '' }}>Девочка</option>
                                </optgroup>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                В зависимости от возраста будут допустимы только соответствующие варианты.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Имя пользователя (без цифр)</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Аватар</label>
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*" required>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">JPEG, PNG, GIF, до 2 МБ</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Минимум 8 символов, русские и английские буквы, название текущего месяца ({{ \Carbon\Carbon::now()->format('F') }}), 2 спецсимвола не подряд.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-user-plus me-2"></i>Зарегистрироваться
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">Уже есть аккаунт?
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Войти</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    const birthInput = document.getElementById('birth_date');
    const genderSelect = document.querySelector('select[name="gender"]');

    if (!birthInput || !genderSelect) return;

    const adultOptions = [
        { value: 'М', text: 'М' },
        { value: 'Ж', text: 'Ж' },
        { value: 'Мужчина', text: 'Мужчина' },
        { value: 'Женщина', text: 'Женщина' }
    ];
    const childOptions = [
        { value: 'мальчик', text: 'Мальчик' },
        { value: 'девочка', text: 'Девочка' }
    ];

    function parseDate(str) {
        if (!str) return null;
        let parts;
        if (str.includes('.')) {
            parts = str.split('.');
            if (parts.length === 3) {
                return new Date(parts[2], parts[1] - 1, parts[0]);
            }
        } else if (str.includes('-')) {
            parts = str.split('-');
            if (parts.length === 3) {
                return new Date(parts[0], parts[1] - 1, parts[2]);
            }
        }
        return new Date(str);
    }

    function calculateAge(birthDate) {
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    function updateGenderOptions() {
        const rawValue = birthInput.value.trim();
        if (!rawValue) return;

        const birthDate = parseDate(rawValue);
        if (isNaN(birthDate.getTime())) return;

        const age = calculateAge(birthDate);
        const isUnder18 = age < 18;
        const options = isUnder18 ? childOptions : adultOptions;

        const currentValue = genderSelect.value;

        genderSelect.innerHTML = '';
        const emptyOption = document.createElement('option');
        emptyOption.value = '';
        emptyOption.textContent = 'Выберите...';
        genderSelect.appendChild(emptyOption);

        options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt.value;
            option.textContent = opt.text;
            if (opt.value === currentValue) {
                option.selected = true;
            }
            genderSelect.appendChild(option);
        });
    }

    birthInput.addEventListener('input', updateGenderOptions);
    birthInput.addEventListener('change', updateGenderOptions);
    if (birthInput.value) {
        updateGenderOptions();
    }
})();
</script>
@endpush