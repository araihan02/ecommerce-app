<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan User model di-import

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_admin) { // Pastikan user adalah admin
                // Gunakan Sanctum untuk token API jika sudah di-setup
                // Atau generate token sederhana (kurang aman untuk produksi)
                // Untuk Sanctum: $token = $user->createToken('admin-token', ['role:admin'])->plainTextToken;
                // Untuk contoh ini, kita akan generate token sederhana (TIDAK DIREKOMENDASIKAN UNTUK PRODUKSI)
                // Sebagai gantinya, setup Laravel Sanctum sangat direkomendasikan.
                // Untuk tutorial ini, kita akan set flag di client-side setelah login berhasil.
                // Di produksi, selalu gunakan token (Sanctum).

                // Jika pakai Sanctum (setelah php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider")
                // dan User model use HasApiTokens
                $token = $user->createToken('admin_auth_token', ['admin'])->plainTextToken; // ['admin'] adalah ability token
                return response()->json(['token' => $token, 'user' => $user]);
            }
            Auth::logout(); // Logout jika bukan admin tapi kredensial benar
            return response()->json(['message' => 'Unauthorized. Not an admin.'], 403);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Jika menggunakan Sanctum dan token dikirim via header
        // $request->user()->currentAccessToken()->delete();

        // Atau jika hanya mengandalkan session (web guard)
        Auth::guard('web')->logout(); // atau Auth::logout(); jika default guard adalah web

        // Untuk API token-based (Sanctum), client harus menghapus tokennya.
        // Server bisa membatalkan token jika disimpan di DB.
        // $request->user() akan null jika request tidak diautentikasi dengan token valid
        if ($request->user()) {
            $request->user()->tokens()->delete(); // Menghapus semua token untuk user tersebut
        }

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request) // Untuk mendapatkan info user yang login (via token)
    {
        return response()->json($request->user());
    }
}
