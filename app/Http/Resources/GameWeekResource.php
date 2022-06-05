<?php

namespace App\Http\Resources;

use App\Http\Resources\GameMatchCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class GameWeekResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->week_order,
            'matches' => $this->whenLoaded('matches', function() {
                return new GameMatchCollection($this->matches);
            }),
        ];
    }
}
