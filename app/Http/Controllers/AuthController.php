<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);

        if ($token) {
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);
        }
    }

    public function logout()
    {
        return 'Logout';
    }

    public function refresh()
    {
        return 'refresh';
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
