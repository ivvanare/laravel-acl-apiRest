<?php

namespace App\Http\Controllers\Security;

use App\Http\Requests\Security\UserRequest;
use App\Notifications\AccountRestabled;
use App\Notifications\AccountSuspended;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Storage;
use Avatar;
use DB;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all()->where('active',1);

        return response()->json([
            'users' => $users
        ]);
    }

    public function create(UserRequest $request)
    {
        return response()->json([
            'user create'
        ]);
    }

    public function edit($id)
    {
        try {
            $user = User::where('active',1)->findOrFail($id);
            return response()->json(['user' => $user]);
        } catch (\Throwable $th) {
            return response()->json(["error" => "Este usuario no existe", "message" => "Como llegaste hasta aqui???."], 401);
        }
    }

    public function update(Request $request, $id)
    {
 
        Validator::make($request->all(), [
            'name'     => 'required|string',
            'password' => 'sometimes|required|min:8|max:32|confirmed'
        ])->validate();

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->password = bcrypt($request->password);
        $user->save();

        $avatar = Avatar::create($request->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$request->id.'.png', (string) $avatar);

        return response()->json(["success" => "Usuario actualizado", "message" => "Usuario actualizado con exito"]);

    }

    public function show($id)
    {
        $user = User::where('active',1)->findOrFail($id);
        return response()->json(['user' => $user]);
    }

    public function activate($id)
    {
        $user = User::where('active',0)->find($id);
        $user->active = 1;
        $user->save();
        $user->notify(new AccountRestabled($user));

        return response()->json(["success" => "Usuario restablecido", "message" => "Usuario restablecido con exito"]);
    }

    public function desactivate($id)
    {
        $user = User::where('active',1)->find($id);
        $user->active = 0;
        $user->save();
        $user->notify(new AccountSuspended($user));

        return response()->json(["success" => "Usuario suspendido", "message" => "Usuario suspendido con exito"]);
    }

    public function delete($id)
    {
        $userToDelet = User::find($id);

        DB::transaction(function () use ($id,$userToDelet){
            $user = User::find($id)->delete();
            $userToDelet->notify(new AccountSuspended($userToDelet));
        },3);
        
        return response()->json(["success" => "Usuario eliminado", "message" => "Usuario eliminado con exito"]);       
    }
}
