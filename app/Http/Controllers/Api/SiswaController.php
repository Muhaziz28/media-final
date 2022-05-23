<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Data siswa berhasil diambil');
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'nis' => 'required', 
                'password' => 'required'
            ]);

            $credentials = request(['nis', 'password']);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Authentication failed'
                ], 'Authentication gagal', 500);
            }

            $siswa = User::where('nis', $request->nis)->first();
            if(! Hash::check($request->password, $siswa->password, [])) {
                throw new \Exception('Invalid Crendentials');
            }

            $tokenResult = $siswa->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'siswa' => $siswa
            ],'Login berhasil');
        }catch(\Exception $err){
            return ResponseFormatter::error([
                'message' => 'Terjadi kesalahan',
                'error' => $err,
            ], 'Login gagal', 500);
        }
    }

    public function logout(Request $request) {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
