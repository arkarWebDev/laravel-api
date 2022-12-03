<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::latest('id')->get();

        // return response()->json($photos);
        return PhotoResource::collection($photos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'photos' => 'required',
            'photos.*' => 'mimes:png,jpg|file|max:512'
        ]);

        foreach($request->file('photos') as $key => $photo){
            $photo_name = $photo->store('public');
            Photo::create([
                'product_id' => $request->product_id,
                'name' =>  $photo_name
            ]);
        }

        return response()->json(['message'=>'photos uploaded successfully.'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::find($id);
        if(is_null($photo)){
            return response()->json(['message' => 'no vaild photo with this id !'],404);
        }
        $photo->delete();

        return response()->json([],204);
    }
}