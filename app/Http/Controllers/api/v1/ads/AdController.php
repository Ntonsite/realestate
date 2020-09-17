<?php

namespace App\Http\Controllers\api\v1\ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ad;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Ad::all();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);

        $ad = new Ad();
        $ad ->title= $request->title;
        $ad ->description= $request->description;

        if(Auth::user()->ads()->save($ad)){
            return response()->json([
                'success' => true,
                'data' => $ad->toArray()
            ], 200);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Ad was not created'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ad = Auth::user()->ads()->find($id);
 
        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ad = Auth::user()->ads()->find($id);
 
        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Ad not found'
            ], 400);
        }
 
        $updated = $ad->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Ad can not be updated'
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Auth::user()->ads()->find($id);
 
        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Ad not found'
            ], 400);
        }
 
        if ($ad->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Ad deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ad can not be deleted'
            ], 500);
        }
    }
}
