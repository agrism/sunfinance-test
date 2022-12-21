<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientCollectionResource;
use App\Http\Resources\ClientResource;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class ClientController
 * @package App\Http\Controllers\Private
 */
class ClientController extends Controller
{
    public function __construct(readonly private ClientRepositoryInterface $clientRepository)
    {
    }

    /**
     * List clients
     *
     * @OA\Get(
     *     path="/api/private/client",
     *     tags={"Private API client"},
     *     operationId="privateListClients",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="number of active page",
     *         required=false,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ClientCollectionResource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *     )
     * )
     */

    public function index(): JsonResponse
    {
        $resource = new ClientCollectionResource(
            $this->clientRepository->paginate(config('app.perPage'))
        );

        return response()->json($resource, Response::HTTP_OK);
    }

    /**
     * Show client
     *
     * @OA\Get(
     *     path="/api/private/client/{clientId}",
     *     tags={"Private API client"},
     *     operationId="privateShowClient",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         description="ID of customer that needs to show",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             ref="#/components/schemas/ClientResource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *     )
     * )
     */
    public function show(int $id): JsonResponse|Response
    {
        if(!$client = $this->clientRepository->find($id)){
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        return response()->json(new ClientResource($client), Response::HTTP_OK);
    }
}
