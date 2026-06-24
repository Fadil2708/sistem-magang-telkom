<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'id'       => (string) Str::uuid(),
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'intern',
                'is_active' => true,
            ]);

            $user->internProfile()->create([
                'id' => (string) Str::uuid(),
                'full_name' => explode('@', $request->email)[0],
            ]);

            return $user->load('internProfile', 'supervisorProfile');
        });

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => new UserResource($user),
        ], 'Registrasi berhasil.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user()->load('internProfile', 'supervisorProfile');

        if (!$user->is_active) {
            return $this->error('Akun Anda telah dinonaktifkan.', 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->error('Silakan verifikasi email Anda terlebih dahulu.', 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user'  => new UserResource($user),
        ], 'Login berhasil.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout berhasil.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? $this->success(null, 'Link reset password telah dikirim ke email Anda.')
            : $this->error('Gagal mengirim link reset password.', 400);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->success(null, 'Password berhasil direset.')
            : $this->error('Token reset password tidak valid atau sudah kadaluarsa.', 400);
    }
}
