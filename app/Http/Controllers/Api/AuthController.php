<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function registerHeader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Генерируем уникальный API токен
        $apiToken = Str::random(60);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => $apiToken, // Сохраняем токен
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Пользователь зарегистрирован',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'api_token' => $apiToken, // Возвращаем токен
            'token_type' => 'Bearer'
        ], 201);
    }

    // Логин с получением API токена
    public function loginHeader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверные учетные данные'
            ], 401);
        }

        // Генерируем новый токен при каждом входе
        $apiToken = Str::random(60);
        $user->update(['api_token' => $apiToken]);

        return response()->json([
            'status' => 'success',
            'message' => 'Успешный вход',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ],
            'api_token' => $apiToken,
            'token_type' => 'Bearer'
        ]);
    }

    // Профиль пользователя (требует токен в заголовке)
    public function profileHeader(Request $request)
    {
        // Токен проверяется middleware
        return response()->json([
            'status' => 'success',
            'user' => $request->user(),
            'auth_method' => 'Header (API Token)',
            'message' => 'Доступ разрешён через заголовок Authorization'
        ]);
    }

    /**
     * ============================================
     * 🔐 2. JWT АВТОРИЗАЦИЯ
     * ============================================
     * Использует подписанные токены с временем жизни
     */

    // Регистрация с JWT
    public function registerJwt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Генерируем JWT токен
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Пользователь зарегистрирован (JWT)',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 . ' seconds'
        ], 201);
    }

    // Логин с JWT
    public function loginJwt(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверные учетные данные'
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Успешный вход (JWT)',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->is_admin,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 . ' seconds'
        ]);
    }

    // Профиль через JWT
    public function profileJwt(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'user' => auth()->user(),
            'auth_method' => 'JWT',
            'message' => 'Доступ разрешён через JWT токен',
            'token_expires_at' => now()->addMinutes(JWTAuth::factory()->getTTL())->toDateTimeString()
        ]);
    }

    // Обновление JWT токена
    public function refreshJwt()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            
            return response()->json([
                'status' => 'success',
                'access_token' => $newToken,
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60 . ' seconds'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не удалось обновить токен'
            ], 401);
        }
    }

    // Выход (инвалидирует JWT)
    public function logoutJwt()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return response()->json([
                'status' => 'success',
                'message' => 'Успешный выход из системы'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка при выходе'
            ], 500);
        }
    }
}
