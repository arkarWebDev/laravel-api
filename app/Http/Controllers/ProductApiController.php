<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest('id')->paginate(8);
        // return response()->json($products);
        return ProductResource::collection($products);
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
            "name" => "required|min:3|max:20",
            "price" => "required|numeric|min:1|max:1000000",
            "stock" => "required|numeric|min:1|max:100",
            'photos' => 'required',
            'photos.*' => 'mimes:png,jpg|file|max:512'
        ]);

        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
            "user_id" => Auth::id()
        ]);

        $photos = [];
        foreach($request->file('photos') as $key => $photo){
            $photo_name = $photo->store('public');
            $photos[$key] = new Photo(['name' => $photo_name]);
        }

        $product->photos()->saveMany($photos);
        
        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(["message" => "Product not found on database"],404);
        }
        // return response()->json($product);
        return new ProductResource($product);
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
        $request->validate([
            "name" => "nullable|min:3|max:20",
            "price" => "nullable|numeric|min:1|max:1000000",
            "stock" => "nullable|numeric|min:1|max:100",
        ]);

        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(["message" => "Product not found on database"],404);
        }

        if($request->has('name')){
            $product->name = $request->name;
        }

        if($request->has('price')){
            $product->price = $request->price;
        }

        if($request->has('stock')){
             $product->stock = $request->stock;
        }

        $product->update();
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if(is_null($product)){
            return response()->json(["message" => "Product not found on database"],404);
        }
        $product->delete();
        return response()->json(["message" => "Product is deleted."],204);
    }
}