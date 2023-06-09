<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App\RoleUser;

class LoginController extends Controller
{
    public function login(Request $request){ 

        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }           
        
        try {
            if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
                $auth = Auth::user(); 
                $user = User::findOrFail($auth->id);

                $roles = RoleUser::where('user_id', $auth->id)->get();
                foreach($roles as $role){
                    $role->user;
                    $role->role;
                }

               // $role = $auth->role->name;

                $success['token'] =  $auth->createToken('FinanzasWebApi')->accessToken; 
                $success['authenticated'] = true;
                $success['user_id'] = $auth->id;
                $success['roles'] = $roles;
                $success['isAdmin'] = false;

                foreach($roles as $role){
                    if($role->role->slug == "admin"){
                        $success['isAdmin'] = true;
                    }
                }
            
               // $success['role'] = $auth->role->name;
                $success['name'] = $auth->name;
                return response()->json([ 'success' => $success ], 200); 

            } else { 
                return response()->json([ 'error' => 'Sin autorización, por favor revise su información.' ], 401); 
            } 
        } catch (Exception $e) {
            return response()->json(["error" => "Imposible iniciar sesión, presione F5 e intente nuevamente"], 500);
        }
    }
}
