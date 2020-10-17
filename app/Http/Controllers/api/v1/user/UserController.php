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
        $validator = $this->validateUser(true, $request);

        if ($validator->fails()) {
            return response(AppHelper::appResponseWithValidation($validator, []));
        }

        $password = $request['password'];
        $request['password'] = Hash::make($password);
        $request['name'] = $request['first_name'] . ' ' . $request['last_name'];

        if (User::create($request->toArray())) {
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


        $validator = $this->validateUser(false, $request);
        if (!$validator->fails()) {


            User::where('id', Auth::id())->update([
                'account_type->Customer' => $request->input('account_type.Customer'),
                'account_type->Client' => $request->input('account_type.Client'),
                'phone->Phone1' => $request->input('phone.Phone1'),
                'phone->Phone2' => $request->input('phone.Phone2'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            ]);
            $user = User::where('id', Auth::id())->first();
            return response(AppHelper::appResponse(false, null, ["user" => $user]));

        }
        return response(AppHelper::appResponseWithValidation($validator, []));


    }

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();

        return response(['message' => 'Successfully Loggged Out'], 200);

    }


    public function destroy(Request $request)
    {

        User::where('id', Auth::id())->delete();
        $this->logout($request);

    }

    private function validateUser($isNewUser, Request $request)
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
            'business_mail' => ['sometimes', 'string', 'email', 'unique:users', 'max:255'],
            'phone.Phone1' => ['required'],
            'phone.Phone2' => ['required'],
            'country' => ['required', 'in:Tz,Bw'],
            'image' => ['sometimes'],
            'account_type.Customer' => ['sometimes'],
            'account_type.Client' => ['sometimes'],
            'account_type.Pro' => ['sometimes'],
            'account_type.Dalali' => ['sometimes'],
            'favorites.favorite_list' => ['sometimes'],
        ];

        return $isNewUser ? Validator::make(request()->all(), $newUser) : Validator::make(request()->all(), $updateUser);
    }
}
