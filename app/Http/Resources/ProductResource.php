<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function check_stock_status($stockCount){
        $stock_status = "";

        if($stockCount > 50){
            $stock_status = "available";
        }else if($stockCount <= 50){
            $stock_status = "few stock";
        }else if($stockCount == 0){
            $stock_status = "Out of stock";
        }

        return $stock_status;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->name,
            'price' => $this->price,
            'price_by_mmk' => $this->price . " MMK",
            'stock' => $this->stock,
            'stock_status' => $this->check_stock_status($this->stock),
            'product_seller' => new UserResource($this->user),
            'product_photos' => PhotoResource::collection($this->photos)

        ];
    }
}