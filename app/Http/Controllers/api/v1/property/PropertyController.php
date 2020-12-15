<?php

namespace App\Http\Controllers\api\v1\property;

use App\Http\Controllers\api\v1\AppHelper;
use App\Http\Controllers\Controller;
use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{

    public function index()
    {
        $property = Property::with(['user'])->orderByDesc('id')->get();
        return AppHelper::appResponse(false, null, ['property' => $property]);
    }

    public function type($type)
    {
        $property = Property::with(['user'])->where('type', $type)->orderByDesc('id')->get();
        return AppHelper::appResponse(false, null, ['property' => $property]);
    }

    public function userProperty()
    {
        $userId = auth()->user()->id;
        $property = Property::with(['user'])->where('user_id', $userId)->orderByDesc('id')->get();
        return AppHelper::appResponse(false, null, ['property' => $property]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //sleep(3);
        $userID = Auth::id();

        $validator = $this->validateProperty();

        if ($validator->fails()) {
            return AppHelper::appResponseWithValidation($validator, []);
        }

        $images = [];
        if ($request->hasFile('images')) {
            $imagesFile = Collection::wrap($request->file('images'));
            $helper = new AppHelper();
            $images["images"] = $helper->storeImages($imagesFile, Auth::id());
            $images["videos"] = [];
        }else{
            $images = null;
        }

        $propertyID = Property::insertGetId([
            'user_id' => $userID,
            'rental_frequency' => json_encode($request->input('rental_frequency')),
            'near_by_name' => json_encode($request->input('near_by_name')),
            'category' => $request->input('category'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'offer' => json_encode($request->input('offer')),
            'location' => $request->input('location'),
            'bedroom' => $request->input('bedroom'),
            'bathroom' => $request->input('bathroom'),
            'media' => json_encode($images),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $property = Property::where('id', $propertyID)->with(['user'])->first();
        return AppHelper::appResponse(false, null, ['property' => $property]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        //
    }

    private function validateProperty()
    {
        $rules = [
            "rental_frequency.yearly" => "sometimes",
            "rental_frequency.monthly" => "sometimes",
            "rental_frequency.weekly" => "sometimes",
            "rental_frequency.daily" => "sometimes",
            "near_by_name" => "required",
            "category" => "required",
            "type" => "required",
            "description" => "required",
            "offer.long_stay_night" => "sometimes",
            "offer.offer_percentage" => "sometimes",
            "location" => "required:min:3",
            "bedroom" => "required|min:0",
            "bathroom" => "required|min:0",
        ];

        return Validator::make(request()->all(), $rules);
    }
}
