<?php

namespace App\Http\Resources;

use App\Http\Resources\GameMatchResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GameMatchCollection extends ResourceCollection
{
    
    public $collects = GameMatchResource::class;
    
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
