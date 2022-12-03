<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function all()
    {
        return response()->json([
            'msg'=>'Non connecté',
            'status_code' => 200
            ]);
    }

    // methode d'inscription
    public function InscrisUtilisateur(Request $request)
    {
        $utilisateur = new User;

        $utilisateur->first_name = $request->first_name;
        $utilisateur->last_name = $request->last_name;
        //$utilisateur->phone_number = $request->phone_number;
        $utilisateur->email =   $request->email;
        $utilisateur->username =   $request->user_name;
        $utilisateur->password  = bcrypt($request->password);

        $utilisateur->save();

        return response()->json([
            'msg'=>'Utilisateur creation reussi',
            'status_code' => 200,
            'utilisateur'=> $utilisateur
            ]);

    }

    // methode d'authentification

    public function connexion(Request $request)
    {
    try {
        $request->validate([
        'username' => 'required',
        'password' => 'required'
        ]);

        $credentials = request(['username', 'password']);

        if (!Auth::attempt($credentials)) {
        return response()->json([
            'status_code' => 500,
            'message' => 'unauthorized',
            'error' => $error,
        ]);
        }

        $user = User::where('username', $request->username)->first();

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return response()->json([
        'status_code' => 200,
        'access_token' => $tokenResult,
        'token_type' => 'Bearer',
        'username'=> $user->username,
        'firstname'=> $user->first_name,
        'lastname'=>$user->last_name,
        'id'=> $user->id,
        'email'=>$user->email,

        ]);

    } catch (Exception $error) {
        return response()->json([
        'status_code' => 500,
        'message' => 'Error in Login',
        'error' => $error,
        ]);
    }
    }


    public function ff(Request $request){

    }
}