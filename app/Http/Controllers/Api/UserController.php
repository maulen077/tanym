<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function registerSendCode (Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users'
        ]);

        Cache::put('register#' . $request->phone, 1111, 600);

        return response()->json([
           'message' => 'Успешно'
        ]);
    }

    public function registerCheck (Request $request)
    {
        $request->validate([
            'phone' => 'required:unique:users',
            'code' => 'required'
        ]);

        $register = Cache::get('register#' . $request->phone);

        //phone not found
        if (!$register) {
            return response()->json([
               'mesage' => 'Такой номер не найден'
            ], 422);
        }

        if ((int)$request->code != $register) {
            return response()->json([
                'message' => 'Неправильный код'
            ], 422);
        }

        return response()->json([
            'message' => 'Правильный код'
        ]);


    }

    public function register(Request $request)
    {

       $request->validate([
           'city_id' => 'required|exists:cities,id',
           'name' => 'required|string|max:255',
           'phone' => 'required|unique:users',
           'password' => 'required|string|min:6',
           'email' => 'required|string|email|max:255|unique:users',
           'language' => 'required|string|max:255',
           'role' => ['required', Rule::in(['admin', 'user'])],
        ]);

        $user =  User::create([
            'city_id' => $request->city_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
          //  'access_token' => Str::random(60),
            'email' => $request->email,
            'language' => $request->language,
            'role' => $request->role,
        ]);
        //token , user return
        $token = $user->createToken('API_USERS')->plainTextToken;
        User::whereId($user->id)->update(['access_token' => $token]);
        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован',
            'user' => $user,
            'access_token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request['phone'])->first();

        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('API_USERS')->plainTextToken;
        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ],
        ]);
    }

    public function lateAuth(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }

        $token = $user->createToken('API_USERS')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $token,
            ],
        ]);
    }


    public function passwordResetSendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users'
        ]);

        Cache::put('register#' . $request->phone, 1111, 600);

        return response()->json([
            'message' => 'Успешно'
        ]);
    }

    public function passwordResetCheckCode(Request $request)
    {
        $request->validate([
            'phone' => 'required:exists:users',
            'code' => 'required'
        ]);

        $resetCheckCode = Cache::get('register#' . $request->phone);

        if (!$resetCheckCode) {
            return response()->json([
                'mesage' => 'Такой номер не найден'
            ], 422);
        }

        if ((int)$request->code != $resetCheckCode) {
            return response()->json([
                'message' => 'Неправильный код'
            ], 400);
        }

        return response()->json([
            'message' => 'Правильный код'
        ],200);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'phone' => 'required'
        ]);

        User::wherePhone($request['phone'])->update(['password' => bcrypt($request['password'])]);

        return response()->json([
            'message' => 'Успешно'
        ], 200);

    }

    public function edit(Request $request)
    {
        $user = User::whereId($request->user()->id)->first();
        if ($request->name){
            $user->name = $request->name;
        }
        if ($request->phone){
            $user->phone = $request->phone;
        }
        if ($request->email){
            $user->email = $request->email;
        }
        if ($request->avatar){
            $user->avatar = $request->avatar;
        }
        if ($request->city_id){
            $user->city_id = $request->city_id;
        }
        $user->save();

        return response()->json(
            $user, 200
        );
    }

    public function delete(Request $request)
    {
         User::whereId($request->user()->id)->delete();
         return response()->json(
             'успешно', 200
             );
    }

}
