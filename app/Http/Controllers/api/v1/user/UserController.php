<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\api\v1\AppHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        $user = User::with(['notifications'])->get();
        return AppHelper::appResponse(false, null, [
            'user' => $user
        ]);
    }


    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($login)) {
            return response(['message' => 'Invalid login credentials']);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(), 'access_token' => $accessToken]);

    }


    public function register(Request $request)
    {
        $validator = $this->validateUser(true);

        if ($validator->fails()) {
            return  response(AppHelper::appResponseWithValidation($validator,[]));
        }

        $password = $request['password'];
        $request['password'] = Hash::make($password);
        $request['name'] = $request['first_name'].' '.$request['last_name'];

        if(User::create($request->toArray())){
            $request['password'] = $password;
            return AppHelper::userLogin(request(['email', 'password']));
        }

        return response(AppHelper::appResponse(true, "something went wrong", []));
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        //
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        return response(AppHelper::appResponse(false, null, ["user" => $user]));
    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();

        return response(['message' => 'Successfully Loggged Out'], 200);

    }


    public function destroy(User $user)
    {
        //
    }

    private function validateUser($isNewUser)
    {

        $newUser = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8',],
        ];

        $updateUser = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'business_mail' => ['sometimes', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:10', 'min:9'],
            'county' => ['required','in:Tz,Bw'],
        ];

        return $isNewUser ? Validator::make(request()->all(), $newUser) : Validator::make(request()->all(), $updateUser);
    }
}
