<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Session;
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

        $userName = $request->input("username");
        $password = $request->input("password");
        $systemId = $request->input("system_id");
        $ipAdress = $request->ip();
        $userAgent = $request->header("User-Agent");

        try{
            $user = User::where("username", $userName)
            ->where("status", 1)
            ->whereHas("systems", function($query) use ($systemId){
                $query->where("systems.id", $systemId);
            })
            ->first();

            if(!$user){
                return response()->json([
                    "error" => true,
                    "message" => "El usuario no existe",
                    "message_detail" => ""
                ],400); 
            }

            if(!Hash::check($password, $user->password)){
                return response()->json([
                    "error" => true,
                    "message" => "Credenciales incorrectas",
                    "message_detail" => ""
                ],400); 
            }

            $userSystemRoleId = $user->systems()->first()->pivot->id;
    
            $token = JWTAuth::fromUser($user);


            $session = new Session();
            $session->ip_adress = $ipAdress;
            $session->user_agent = $userAgent;
            $session->auth_token = $token;
            $session->status = 1;
            $session->created_by;
          
            
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
