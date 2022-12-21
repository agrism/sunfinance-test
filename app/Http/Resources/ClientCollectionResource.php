<?php

namespace App\Http\Resources;

use App\Models\Client;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  @OA\Property(property="count", type="integer", format="integer", description="record count per page", example="2"),
 *  @OA\Property(property="total", type="integer", format="integer", description="total count of records", example="10"),
 *  @OA\Property(property="prev", type="string", format="link", description="url to previous page", example="/api/private/client?page=1"),
 *  @OA\Property(property="next", type="string", format="link", description="url to next page", example="/api/private/client?page=3"),
 *  @OA\Property(
 *      property="data",
 *      type="array",
 *      @OA\Items(
 *          type="object",
 *          ref="#/components/schemas/ClientResource"
 *      )
 *  )
 * )
 *
 * Class ClientCollectionResource
 * @package App\Http\Resources
 */
class ClientCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
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
            'data' => $this->collection->map(function (Client $client) {
                return new ClientResource($client);
            }),
        ];
    }
}
