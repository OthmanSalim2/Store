<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    // it's be shape of request that will return
    public function toArray(Request $request): array
    {
        // Here hidden and appends variables don't work
        // Here always will return array
        return [
            // $this refer on model current it's Product Model
            'id' => $this->id,
            'name' => $this->name,
            'price' => [
                'normal' => $this->price,
                'compare' => $this->compare_price,
            ],
            'description' => $this->description,
            'image' => $this->image_url,
            'relations' => [
                'category' => [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ],
                'store' => [
                    'store' => [
                        'id' => $this->store->id,
                        'name' => $this->store->name,
                    ],
                ]
            ]
        ];
    }
}
