<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        private readonly SmsService $smsService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::where('phone', $request->phone)->first();
        $isResend = $user !== null;

        if ($user && $user->phone_verified_at) {
            return response()->json([
                'message' => 'Bu telefon raqam allaqachon tasdiqlangan. Iltimos, login qiling.',
            ], 409);
        }

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'password' => $request->password,
            ]);
        }

        $otp = $this->generateOtp($request->phone);

        try {
            $this->smsService->sendOtpCode($request->phone, $otp->code);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'OTP kodni SMS orqali yuborishda xatolik yuz berdi',
            ], 500);
        }

        return response()->json([
            'message' => 'Telefon raqamni tasdiqlash uchun OTP kod yuborildi',
            'phone' => $request->phone,
            'is_resend' => $isResend,
        ], $isResend ? 200 : 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Telefon raqam yoki parol noto\'g\'ri',
            ], 401);
        }

        if (!$user->phone_verified_at) {
            return response()->json([
                'message' => 'Telefon raqam tasdiqlanmagan. Iltimos, avval ro\'yxatdan o\'ting.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Muvaffaqiyatli kirildi',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $otp = OtpCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp || !$otp->isValid()) {
            return response()->json([
                'message' => 'OTP kod noto\'g\'ri yoki muddati o\'tgan',
            ], 422);
        }

        $otp->update(['used' => true]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Foydalanuvchi topilmadi',
            ], 404);
        }

        $user->update(['phone_verified_at' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Telefon raqam muvaffaqiyatli tasdiqlandi',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Muvaffaqiyatli chiqildi',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $request->user()->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Profil muvaffaqiyatli yangilandi',
            'user' => $request->user()->fresh(),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json([
                'message' => 'Joriy parol noto\'g\'ri',
            ], 422);
        }

        $request->user()->update([
            'password' => $request->password,
        ]);

        return response()->json([
            'message' => 'Parol muvaffaqiyatli o\'zgartirildi',
        ]);
    }

    public function deleteProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Profil muvaffaqiyatli o\'chirildi',
        ]);
    }

    private function generateOtp(string $phone): OtpCode
    {
        OtpCode::where('phone', $phone)
            ->where('used', false)
            ->update(['used' => true]);

        return OtpCode::create([
            'phone' => $phone,
            'code' => str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(5),
        ]);
    }
}
