<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            "username" => "required|string",
            "password" => [
                "required",
                "string",
                /*Password::min(8)->mixedCase()
                ->numbers()
                ->symbols()*/
            ],
            "system_id" => "required|integer"
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => true,
                "message" => "Error en validación",
                "message_detail" => $validator->errors()->all() 
            ],400);
        }

        try{
            $user = User::where("username", $request->username)
            ->where("status", 1)
            ->first();

            if(!$user){
                return response()->json([
                    "error" => true,
                    "message" => "El usuario no existe",
                    "message_detail" => ""
                ],400); 
            }

            if(!Hash::check($request->password, $user->password)){
                return response()->json([
                    "error" => true,
                    "message" => "Credenciales incorrectas",
                    "message_detail" => ""
                ],400); 
            }

            $token = JWTAuth::fromUser($user);
            
            return response()->json([
                "success" => true,
                "token" => $token
            ]);

        }catch(Throwable $ex){
            return response()->json([
                "error" => true,
                "message" => "Problema en el proceso de logueo",
                "message_detail" => $ex->getMessage() 
            ],500); 
        }
    }
}
