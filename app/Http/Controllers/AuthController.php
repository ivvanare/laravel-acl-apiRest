<?php

namespace App\Http\Controllers;

use App\Http\Requests\Security\UserRequest;
use App\Notifications\AccountActivated;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Storage;
use Avatar;
use DB;

class AuthController extends Controller
{

    public function signup(UserRequest $request)
    {
        DB::beginTransaction();

        try{
            $user = new User();
            $user->name     = $request->get('name');
            $user->email    = $request->get('email');
            $user->password = bcrypt($request->get('password'));
            $user->activation_token  = str_random(60);
            $user->save(); //save data

            $avatar = Avatar::create($user->name)->getImageObject()->encode('png'); //create avatar
            $avatarRoute = 'avatars/'.$user->id.'.png';
            Storage::put($avatarRoute, (string) $avatar); //save avatar in storage/avatars
            $user->avatar = $avatarRoute; //save in db
            $user->save(); //save data

            DB::commit();

            $user->notify(new SignupActivate($user)); //notifica created account 

            return response()->json(['success' => 'Registro exitoso','message' => 'Para continuar confirme su correo electronico  !'], 201);
                
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'error' => $th
            ]);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (Auth::attempt($credentials)) {

            $user = $request->user();
            $tokenResult = $user->createToken('Token Acceso Personal');
            $token = $tokenResult->token;
            $expiration = Carbon::parse($token->expires_at)->toDateTimeString();//1 year later staring now

            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => $expiration,
                'user'         => $user,
                //'url'          =>  redirect()->intended()
            ]);
        }
        return response()->json(["error" => "credenciales invalidas", "message" => "Correo o contraseña incorectas."]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['success' => 'Cierre exitoso','message' => 'Cierre de sesión exitoso']);
    }

    public function signupActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json(['error' => 'Token invalido','message' => 'El token de activación es inválido'], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->email_verified_at = Carbon::now();
        $user->save();
        $user->notify(new AccountActivated($user));
        return $user;
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
