<?php

namespace App\Http\Controllers\api\v1\user;
use Carbon\Exceptions\Exception;
use App\Http\Controllers\api\v1\AppHelper;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $req = new Request();
        $req['email'] = $request->input('email');
        $req['password'] = $request->input('password');
        if (!$validator->fails()) {
            return AppHelper::userLogin($req->all());
        }
        return AppHelper::appResponseWithValidation($validator, []);
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

        User::create([
            'name' => request()->name,
            'first_name' => request()->first_name,
            'last_name' => request()->last_name,
            'email' => request()->email,
            'country' => request()->country,
            'verified_token' => rand(1000,9999),
            'password' => request()->password,
        ]);

        $user = User::where('email',request()->email)->first();

        //todo uncomment for production
       // $this->sendEmailVerification($user);

        $request['password'] = $password;
        return AppHelper::userLogin(request(['email', 'password']));

    }

    private function sendEmailVerification(User $user){



        Mail::send('email.email_verified_template', ["user"=>$user], function($message) use($user)
        {
            $message->to($user->email, $user->first_name)->subject("Verify Email Address");
        });
}

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),['verification_token' => 'required']);

        if($validator->fails()){
            return response()->json(
                AppHelper::appResponseWithValidation($validator, [])
            );
        }
        $userID = Auth::id();

        $user = User::where('verified_token',$request->input('verification_token'))->where('id', $userID)->first();

        if(!$user){
            return AppHelper::appResponse(true, 'Verification fail',['user' => Auth::user()]);
        }
        if(!$user->hasVerifiedEmail()){
            $user->markEmailAsVerified();
            //todo remove verification token
        }

        $user = User::where('id', $userID)->first();
        return AppHelper::appResponse(false, null,['user' => $user]);

    }

    public function resendEmail(){
        $user = User::where('id', Auth::id())->first();
        if($user){
            $user->update([
                //'verified_token' => Str::random(60),
                'email_verified_at' => null
            ]);
            $this->sendEmailVerification($user);
        }

    }


    public function forgotPassword(Request $request){
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json(AppHelper::appResponseWithValidation($validator,[]));
        } else {
            try {
                $response = Password::sendResetLink($request->only('email'));
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return response()->json(AppHelper::appResponse(false,trans($response),[]));
                    case Password::INVALID_USER:
                        return response()->json(AppHelper::appResponse(true,trans($response),[]));
                }
            } catch (\Swift_TransportException $ex) {
                return response()->json(AppHelper::appResponse(true,$ex->getMessage(),[]));
            } catch (Exception $ex) {
                return response()->json(AppHelper::appResponse(true,$ex->getMessage(),[]));
            }
        }
    }

    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        //
    }

    public function uploadImage()
    {
        //sleep(3);
        $auth = Auth::id();
        $directory_one = '/property_images/user_' . $auth;
        $directory = '/property_images/user_' . $auth . "/profile";

        if (!is_dir(public_path($directory_one))) {
            mkdir(public_path($directory_one), 0777);
        }
        if (!is_dir(public_path($directory))) {
            mkdir(public_path($directory), 0777);
        }
        //delete all file inside directory
        File::deleteDirectory(public_path($directory), true);


        $image = request()->file('image');
        $original = Str::random() . 'user_pic.' . $image->getClientOriginalExtension();
        $image->move(public_path($directory), $original);

        User::where('id', $auth)->update([
            "image" => $directory . '/' . $original,
        ]);

        $user = User::where('id', $auth)->first();


        return response()->json(
            AppHelper::appResponse(false, "success", ['user' => $user])
        );
    }


    public function update(Request $request)
    {

        //sleep(3);

        $validator = $this->validateUser(false, $request);

        if (!$validator->fails()) {
            User::where('id', Auth::id())->update([
                'account_type->Customer' => $request->input('account_type.Customer'),
                'account_type->Client' => $request->input('account_type.Client'),
                'phone->Phone1' => $request->input('phone.Phone1'),
                'phone->Phone2' => $request->input('phone.Phone2'),
                'business_email' => $request->input('business_email'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'country' => $request->input('country'),
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
        return response(AppHelper::appResponse(false, null, []));

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
            'business_email' => ['sometimes', 'string', 'email', "unique:users,business_email," . Auth::id(), 'max:255'],
            'phone.Phone1' => ['required', 'max:12'],
            'phone.Phone2' => ['sometimes', 'max:12'],
            'country' => ['required', 'in:Tz,Bw'],
            'image' => ['sometimes'],
            'account_type.Customer' => ['sometimes'],
            'account_type.Client' => ['sometimes'],
            'account_type.Pro' => ['sometimes'],
            'dalali' => ['sometimes'],
            'client' => ['sometimes'],
            'favorites.favorite_list' => ['sometimes'],
        ];

        return $isNewUser ? Validator::make(request()->all(), $newUser) : Validator::make(request()->all(), $updateUser);
    }
}
