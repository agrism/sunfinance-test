<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *  @OA\Property(property="count", type="integer", format="integer", description="record count per page", example="2"),
 *  @OA\Property(property="total", type="integer", format="integer", description="total count of records", example="10"),
 *  @OA\Property(property="prev", type="string", format="link", description="url to previous page", example="/api/private/notification?page=1"),
 *  @OA\Property(property="next", type="string", format="link", description="url to next page", example="/api/private/notification?page=3"),
 *  @OA\Property(
 *      property="data",
 *      type="array",
 *      @OA\Items(
 *          type="object",
 *          ref="#/components/schemas/NotificationResource"
 *      )
 *  )
 * )
 *
 * Class ClientCollectionResource
 * @package App\Http\Resources
 */
class NotificationCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'count' => $this->count(),
            'total' => $this->total(),
            'prev' => $this->previousPageUrl(),
            'next' => $this->nextPageUrl(),
            'data' => $this->collection->map(function (Notification $notification) {
                return new NotificationResource($notification);
            }),
        ];
    }
}
