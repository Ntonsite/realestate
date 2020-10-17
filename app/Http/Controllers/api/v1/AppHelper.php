<?php


namespace App\Http\Controllers\api\v1;
use App\User;
use Illuminate\Contracts\Validation\Validator as requestValidator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AppHelper
{
    const rootDirectory = '/property_images';
    const userDirectory = '/user_';
    const userProfile = '/profile';


    public static function appResponse(bool $error, $errorMessage, $data)
    {
        $message = $errorMessage != null ? $errorMessage : "Successful";
        return array_merge([
            'error' => $error,
            'message' => $error ? $errorMessage: $message,
        ], $data);
    }


    public static function appResponseWithValidation(requestValidator $validator, $data)
    {
        return array_merge([
            'error' => true,
            'message' => $validator->getMessageBag()->first(),
        ], $data);

    }

    public static function userLogin($credentials){


        if (!Auth::attempt($credentials)) {

            return  AppHelper::appResponse(true, 'Email or password is wrong', []);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        AppHelper::createFolder(Auth::id());
        return appHelper::appResponse(false,null,  appHelper::appResponseUserInfo($accessToken) );
    }


    public static function appResponseUserInfo($token){
        $data = ["user" => auth()->user()];
        $data["user"]["token"] = $token;
        return $data;
    }

    public static function createFolder(int $userId){
        //product_images folder
        if(!is_dir(public_path(AppHelper::rootDirectory))){
            mkdir(public_path(AppHelper::rootDirectory), 0777);
        }
        //user_<user_id> folder
        if(!is_dir(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId))){
            mkdir(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId), 0777);
        }
        //profile folder
        if(!is_dir(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId.AppHelper::userProfile))){
            mkdir(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId.AppHelper::userProfile), 0777);
        }
    }

    public function deleteAllFolder(){
        //delete product_image folder
        File::deleteDirectory(public_path(AppHelper::rootDirectory),true);
        rmdir(public_path(AppHelper::rootDirectory));
    }

    public function deleteUserFolder(int $userId){
        //delete client_ folder
        File::deleteDirectory(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId),true);
        rmdir(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId));

    }

    public function deleteUserProfileFolder(int $userId){
        //delete profile folder
        File::deleteDirectory(public_path(AppHelper::rootDirectory.AppHelper::userDirectory.$userId.AppHelper::userProfile),true);
    }


    public function storeImages(Collection $images, int $userId){

        $directory = AppHelper::rootDirectory.AppHelper::userDirectory.$userId;
        $original_name = [];

        foreach ($images as $image) {

            $basename = Str::random();
            $original = $basename . '.' . $image->getClientOriginalExtension();

            $imageWidth = Image::make($image)->getWidth();
            $imageHeight = Image::make($image)->getHeight();

            array_push($original_name, [
                "url" => $directory . '/' . $original,
                "width" => $imageWidth,
                "height" => $imageHeight
            ]);

            $image->move(public_path($directory), $original);
        }

        $filesData = [];
        foreach ($original_name as $key => $value) {
            array_push($filesData, [ 'image' => $value, ]);
        }

        return $filesData;
    }



    public function storeUserImage(int $userId){

        $directory = AppHelper::rootDirectory.AppHelper::userDirectory.$userId.AppHelper::userProfile;
        //delete all file inside directory
        $this->deleteUserProfileFolder($userId);
        $image = request()->file('image');
        $original = Str::random().'user_pic.' . $image->getClientOriginalExtension();
        $image->move(public_path($directory), $original);

        return $directory.'/'.$original;
    }
}
