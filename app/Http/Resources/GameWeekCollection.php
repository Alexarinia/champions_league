<?php

namespace App\Http\Resources;

use App\Http\Resources\GameWeekResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GameWeekCollection extends ResourceCollection
{
    
    public $collects = GameWeekResource::class;
    
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
