<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array(
                'success' => false,
                'message' => 'Failed to register. Please check your input data',
                'data' => null,
                'errors' => $validator->errors()
            );

            return response()->json($response, 400);
        }

        $user = User::create($validator->validated());
        $response = array(
            'success' => true,
            'message' => 'Successfully register.',
            'data' => $user
        );

        return response()->json($response, 201);
    }

    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array(
                'success' => false,
                'message' => 'Failed to login. Please check your input data',
                'data' => null,
                'errors' => $validator->errors()
            );

            return response()->json($response, 400);
        }

        $credentials = $request->only('name', 'password');
        if (!$token = auth()->attempt($credentials)) {
            $response = array(
                'success' => false,
                'message' => 'Failed to login. Wrong username or password',
                'data' => null,
            );

            return response()->json($response, 400);
        }

        $response = array(
            'success' => true,
            'message' => 'Successfully login.',
            'data' => auth()->guard('api')->user(),
            'accesstoken' => $token
        );

        return response()->json($response, 200);
    }

    public function logout(Request $request) {
        if (auth()->check()) {
            auth()->invalidate(true); // Blacklist token sehingga tidak bisa digunakan lagi
    
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil. Token telah dihapus dan tidak bisa digunakan lagi.'
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada pengguna yang login.'
        ], 400);
    }
    

    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Buat token reset password
        $token = \Str::random(60);
    
        // Simpan token ke database (table password_resets)
        \DB::table('reset_password')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
    
        // Kirim token ke email pengguna
        // Biasanya menggunakan Notifikasi atau Mail
        // Mail::to($request->email)->send(new ResetPasswordMail($token));
    
        return response()->json([
            'success' => true,
            'message' => 'Token reset password telah dikirim ke email Anda.',
            'token' => $token // ⚠️ Sementara ditampilkan untuk testing, hapus di production!
        ], 200);
    }
    

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 400);
        }
    
        // Cek apakah token valid
        $reset = \DB::table('reset_password')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
    
        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah digunakan'
            ], 400);
        }
    
        // Update password user
        \DB::table('users')->where('email', $request->email)->update([
            'password' => bcrypt($request->password)
        ]);
    
        // Hapus token setelah digunakan
        \DB::table('reset_password')->where('email', $request->email)->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset, silakan login dengan password baru.'
        ], 200);
    }
    
}