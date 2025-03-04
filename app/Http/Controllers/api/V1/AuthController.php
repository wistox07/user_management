<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use App\Models\UserSystemRole;
use Dotenv\Repository\RepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                "message" => "Error en validación",
                "message_detail" => $validator->errors()->all()
            ], 400);
        }

        $userName = $request->input("username");
        $password = $request->input("password");
        $systemId = $request->input("system_id");
        $ipAdress = $request->ip();
        $userAgent = $request->header("User-Agent");

        try {

            DB::enableQueryLog();
            DB::beginTransaction();

            //first devuelve un objeto UserSystemRole si hay datos y null , si no hay datos
            //get devuelve una coleccion de UserSystemRole si no hay datos lo que hace que sea true 
            $userSystemRole = UserSystemRole::with(["user.profile","role","system"])
            ->whereHas("user", function ($query) use ($userName) {
                $query->where("username", $userName)
                ->where("status", 1);
            })
            ->where("system_id", $systemId)
            ->where("status", 1)
            ->first();

            $userSystemRoleSystemId = UserSystemRole::whereHas("user", function($query){
                $query->where("username","sistema")
                ->where("status", 1);
            })->value("id");

       
            if(!$userSystemRole){
                return response()->json([
                    "error" => true,
                    "message" => "El usuario no existe",
                    "message_detail" => ""
                ], 400);
            }

            if(!$userSystemRoleSystemId){
                return response()->json([
                    "error" => true,
                    "message" => "No fue posible encontrar el usuario de sistema",
                    "message_detail" => ""
                ], 400);
            }

            

            if (!Hash::check($password, $userSystemRole->user->password)) {
                return response()->json([
                    "error" => true,
                    "message" => "Credenciales incorrectas",
                    "message_detail" => ""
                ], 400);
            }

            $token = JWTAuth::fromUser($userSystemRole->user);


            $session = new Session();
            $session->user_system_role_id = $userSystemRole->id;
            $session->ip_adress = $ipAdress;
            $session->user_agent = $userAgent;
            $session->auth_token = $token;
            $session->status = 1;
            $session->created_by = $userSystemRoleSystemId;
            $session->save();

            DB::commit();

            return response()->json([
                "success" => true,
                "data" => $userSystemRole,
                "token" => $token
            ]);
        } catch (Throwable $ex) {
            DB::rollBack();
            return response()->json([
                "error" => true,
                "message" => "Problema en el proceso de logueo",
                "message_detail" => $ex->getMessage()
            ], 500);
        }
    }
}
