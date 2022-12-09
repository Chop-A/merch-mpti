<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function register(Request $request)
    {
        $name = $request->input('name');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $cekEmail = User::where('email','=',$email)->first();
        if(!empty($cekEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exist'
            ], 400);
        } if(!$name || !$username || !$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Field empty'
            ], 400);
        }
        $register = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);
        if($register) {
            return response()->json([
                'success' => true,
                'message' => 'Register Success!',
                'data' => [
                    'token' => 'Token akan diberikan setelah login.'
                ]
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register Failed!',
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if($user){
            if (Hash::check($password, $user->password)) {
                # Update token user
                $jwt = $this->jwt(
                    [
                        "alg" => "HS256",
                        "typ" => "JWT"
                    ],
                    [
                        "sub" => "{$user->id}:{$user->email}",
                        "name" => $user->name,
                        "iat" => time()
                    ],
                    "Secret"
                );

                $user->token = $jwt;
                //$user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Login Success!',
                    'data' => [
                        'token' => $jwt
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password doesnt match!'
                ], 400);
            }
        } else if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found!'
            ], 404);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 504);
        }
    }

    private function base64url_encode(string $data): string {
		// TODO: fungsi untuk enkrip json -> base64 -> base64url
        
        // ubah json string menjadi base64
        $base64 = base64_encode($data); 

        // ubah char '+' -> '-' dan '/' -> '_'
        $base64url = strtr($base64, '+/', '-_'); 

        // menghilangkan '=' pada akhir string
        return rtrim($base64url, '='); 
    }

    private function sign(string $header, string $payload, string $secret): string {
		// TODO: fungsi untuk generate signature

        $signature = hash_hmac('sha256', "{$header}.{$payload}", $secret, true);
        $signature_base64url = $this->base64url_encode($signature);

        return $signature_base64url;
    }

    private function jwt(array $header, array $payload, string $secret): string {
		// TODO: fungsi untuk generate jwt

        $header_json = json_encode($header);
        $payload_json = json_encode($payload);

        $header_base64url = $this->base64url_encode($header_json);
        $payload_base64url = $this->base64url_encode($payload_json);
        $signature_base64url = $this->sign($header_base64url, $payload_base64url, $secret);

        $jwt = "{$header_base64url}.{$payload_base64url}.{$signature_base64url}";

        return $jwt;
    }
}
