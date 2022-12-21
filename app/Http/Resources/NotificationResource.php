<?php

namespace App\Http\Resources;

use App\ValueObjects\NotificationValueObject;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(ref="#/components/schemas/NotificationValueObject")
 *
 * Class NotificationResource
 * @package App\Http\Resources
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return (array)(new NotificationValueObject(
            $this->id,
            $this->client_id,
            $this->channel,
            $this->content
        ));
    }
}
