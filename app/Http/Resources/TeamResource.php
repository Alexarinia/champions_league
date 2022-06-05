<?php

namespace App\Http\Resources;

use App\Http\Resources\GameMatchCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'stats' => $this->whenLoaded('matches', function() {
                return [
                    'points' => $this->stats['points'],
                    'played' => $this->stats['played'],
                    'won' => $this->stats['won'],
                    'lost' => $this->stats['lost'],
                    'draw' => $this->stats['draw'],
                    'goal_difference' => $this->stats['goal_difference'],
                ];
            }),
            'prediction' => $this->when($this->hasAppended('prediction'), function() {
                return $this->prediction;
            }),
            'goals' => $this->whenPivotLoaded('game_match_team', function () {
                return $this->pivot->goals;
            }),
        ];
    }
}
