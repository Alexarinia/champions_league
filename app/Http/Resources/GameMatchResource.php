<?php

namespace App\Http\Resources;

use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GameMatchResource extends JsonResource
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
            'host' => new TeamResource($this->getHost()),
            'guest' => new TeamResource($this->getGuest()),
            'finished' => $this->isFinished(),
        ];
    }
}
