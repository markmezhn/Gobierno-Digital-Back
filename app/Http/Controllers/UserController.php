<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\RoleUser;
use Validator;
use Mail;

class UserController extends Controller
{

    public function getUsers(){
        try {
            $users = User::all();
            foreach($users as $user){
                $roles = RoleUser::where('user_id', $user->id)->get();
                foreach($roles as $role){
                    $role->role;
                }
                $user['roles'] = $roles;
            }
            return response()->json(["success" => $users], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Imposible mostrar usuarios: ".$e->getMessage()]);
        }
    }

    public function getUser($id){
        try {
            $user = User::findOrFail($id);
            return response()->json(["success" => $user ]);
        } catch (\Exception $e){
            return response()->json(["error" => "Imposible mostrar usuario: ".$e->getMessage()]);
        }
    }

    public function createUser(Request $r){
        $v = Validator::make($r->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'cpassword' => 'required|same:password'
        ]);
        
        if ($v->fails()) {
            return response()->json(['error' => $v->errors()], 400);
        }
        try {
            $r->merge(['password' => bcrypt($r->password)]);
            $user = User::create($r->all());
            return response()->json(["success" => "El usuario nuevo se ha creado correctamente.", "user" => $user], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => "Imposible crear usuario: ".$e->getMessage()]);
        }
    }

    public function updateUser(Request $r, $id) {
        if(!$id) {
            return response()->json(['error' => 'Hubo un error en la solicitud, por favor recargue la página.'], 400);
        }
        $v = Validator::make($r->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'cpassword' => 'required|same:password'
        ]);
        if ($v->fails()) {
            return response()->json(['error' => $v->errors()], 400);
        }
        try {
            if($user = User::findOrFail($id)){
                $r->merge(['password' => bcrypt($r->password)]);
                $user->name = $r->name;
                $user->password = $r->password;
                $user->email = $r->email;
                
                $user->save();
                return response()->json(['success' => 'La información del usuario se ha actualizado correctamente.'], 200);
            } else {
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => "Imposible actualizar usuario: ".$e->getMessage()], 500);
        }
    }

    public function deleteUser($id){
        if(!$id){
            return response()->json(['error' => 'Hubo un error en la solicitud, por favor recargue la página.'], 400);
        }
        try {
            User::destroy($id);
            return response()->json(['success' => 'Usuario borrado correctamente.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Imposible eliminar usuario: '.$e->getMessage()], 500);
        }
    }

    public function changePassword(Request $r){
        $v = Validator::make($r->all(), [
            'password' => 'required',
            'cpassword' => 'required|same:password',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => $v->errors()], 400);
        }

        try{
            $user = User::findOrFail($r->id);
            $user->password = bcrypt($r->password);
            $user->save();
            return response()->json(['success' => 'Su password se ha cambiado.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Imposible cambiar password: '.$e->getMessage()], 500);
        }
    }

    public function assignRole(Request $r){
        $v = Validator::make($r->all(), [
            'role_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json(['error' => $v->errors()], 400);
        }

        try{
            RoleUser::where('user_id',$r->user_id)->delete();
            RoleUser::create($r->all());
            return response()->json(['success' => 'Se ha asignado el rol seleccionado al usuario.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Imposible asignar rol: '.$e->getMessage()], 500);
        }
    }

}
