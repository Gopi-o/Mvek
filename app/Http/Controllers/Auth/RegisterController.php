<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(.)\1{2,}/', $value)) {
                        $fail('Пароль не должен содержать более двух одинаковых символов подряд.');
                    }
                    $common = ['password', '12345678', 'qwertyui', 'qwerty123', '11111111', 'abcd1234'];
                    foreach ($common as $bad) {
                        if (stripos($value, $bad) !== false) {
                            $fail('Пароль содержит слишком распространённую комбинацию.');
                            break;
                        }
                    }
                    $month = strtolower(now()->format('F'));
                    if (stripos($value, $month) === false) {
                        $fail('Пароль должен содержать название текущего месяца на английском ("' . $month . '").');
                    }
                    preg_match_all('/[^a-zA-Z0-9а-яА-ЯёЁ]/u', $value, $specials);
                    $specials = $specials[0] ?? [];
                    if (count($specials) < 2) {
                        $fail('Пароль должен содержать минимум 2 специальных символа.');
                    }
                    for ($i = 0; $i < count($specials) - 1; $i++) {
                        $pos1 = strpos($value, $specials[$i]);
                        $pos2 = strpos($value, $specials[$i+1]);
                        if (abs($pos1 - $pos2) == 1) {
                            $fail('Специальные символы не должны идти подряд.');
                            break;
                        }
                    }
                    if (!preg_match('/[a-zA-Z]/', $value) || !preg_match('/[а-яА-ЯёЁ]/u', $value)) {
                        $fail('Пароль должен содержать символы русской и английской раскладки.');
                    }
                },
            ],
            'phone'      => ['required', 'regex:/^\+[1-9]\d{1,14}$/'],
            'birth_date' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $birth = \Carbon\Carbon::parse($value);
                    $age = $birth->age;
                    if ($age < 0 || $age > 111) {
                        $fail('Возраст должен быть от 0 до 111 лет.');
                    }
                },
            ],
            'gender'     => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $birth = \Carbon\Carbon::parse($request->birth_date);
                    $age = $birth->age;
                    $allowed = $age < 18
                        ? ['мальчик', 'девочка']
                        : ['М', 'Ж', 'Мужчина', 'Женщина'];
                    if (!in_array($value, $allowed)) {
                        $fail($age < 18
                            ? 'Для лиц младше 18 лет выберите "мальчик" или "девочка".'
                            : 'Недопустимое значение пола.');
                    }
                },
            ],
            'username'   => 'required|string|regex:/^[^\d]+$/|unique:users,username',
            'avatar'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048|min:10', // мин 10 байт, макс 2МБ
        ], [
            'phone.regex' => 'Телефон должен быть в международном формате: +XXXXXXXXXXXX',
            'username.regex' => 'Имя пользователя не должно содержать цифр.',
            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Допустимы форматы: jpeg, png, jpg, gif.',
            'avatar.max' => 'Размер аватара не должен превышать 2 МБ.',
            'avatar.min' => 'Размер аватара должен быть не менее 10 байт.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'phone'      => $request->phone,
            'birth_date' => $request->birth_date,
            'gender'     => $request->gender,
            'username'   => $request->username,
            'avatar'     => $avatarPath,
        ]);

        event(new Registered($user));
        auth()->login($user);
        CartService::mergeSessionToUser($user->id);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно!');
    }
}
