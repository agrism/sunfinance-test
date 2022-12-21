<?php

namespace App\Http\Resources;

use App\ValueObjects\ClientValueObject;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(ref="#/components/schemas/ClientValueObject")
 *
 * Class ClientResource
 * @package App\Http\Resources
 */
class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return (array)new ClientValueObject(
            $this->id,
            $this->first_name,
            $this->last_name,
            $this->email,
            $this->phone_number,
        );
    }
}
