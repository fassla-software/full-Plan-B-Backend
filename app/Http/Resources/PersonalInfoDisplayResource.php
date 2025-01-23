<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonalInfoDisplayResource extends JsonResource
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
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "country" => $this?->user_country?->country,
            "country_id" => $this?->country_id,
            "state" => $this?->user_state?->state,
            "state_id" => $this?->state_id,
            "city" => $this?->user_city?->city,
            "city_id" => $this?->city_id,
            "experience_level" => $this?->experience_level ?? '',
            "phone" => $this?->phone,
            "image" =>  asset('assets/uploads/profile/'.$this?->image) ?? '',
        ];
    }
}
