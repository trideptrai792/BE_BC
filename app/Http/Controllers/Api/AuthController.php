<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Đăng nhập: email/username + password -> trả về token
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string', // email hoặc username
            'password' => 'required|string',
        ]);

        // Xác định login theo email hay username
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $request->login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Tài khoản hoặc mật khẩu không đúng.'],
            ]);
        }

        // Xoá token cũ (nếu muốn)
        $user->tokens()->delete();

        // Tạo token mới cho Next.js / mobile
        $token = $user->createToken('web_token')->plainTextToken;

        return response()->json([
            'user'  => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'phone'    => $user->phone,
                'roles'    => $user->roles,
                'avatar'   => $user->avatar,
            ],
            'token' => $token,
        ]);
    }

    // Lấy thông tin user từ token
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    // Đăng xuất: xoá token hiện tại
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đã đăng xuất',
        ]);
    }
}
