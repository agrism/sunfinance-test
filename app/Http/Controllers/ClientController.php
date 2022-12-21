<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStoreRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{
    public function __construct(readonly private ClientRepositoryInterface $clientRepository)
    {
    }

    /**
     * Show client
     *
     * @OA\Get(
     *     path="/api/client/{clientId}",
     *     tags={"Public API client"},
     *     operationId="showClient",
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         description="ID of customer that needs to be show",
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
     * )
     */
    public function show(int $id): JsonResponse|Response
    {
        if(!$client = $this->clientRepository->find($id)){
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        return response()->json(new ClientResource($client), Response::HTTP_OK);
    }

    /**
     * Store new client
     *
     * @OA\Post(
     *     path="/api/client",
     *     tags={"Public API client"},
     *     operationId="storeClient",
     *     @OA\RequestBody(ref="#/components/requestBodies/ClientStoreRequest"),
     *     @OA\Response(
     *      response=422,
     *      ref="#/components/responses/validationErrorResponse422"
     *     ),
     *     @OA\Response(
     *      response=201,
     *      description="Record created"
     *     )
     * )
     */
    public function store(ClientStoreRequest $request): JsonResponse|Response
    {
        $this->clientRepository->createClient([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'phone_number' => $request->input('phoneNumber'),
            'email' => $request->input('email'),
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }

    /**
     * Update client
     *
     * @OA\Put(
     *     path="/api/client/{clientId}",
     *     tags={"Public API client"},
     *     operationId="updateClient",
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         description="ID of customer that needs to be updated",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(ref="#/components/requestBodies/ClientStoreRequest"),
     *     @OA\Response(
     *      response=422,
     *      ref="#/components/responses/validationErrorResponse422"
     *     ),
     *     @OA\Response(
     *      response=204,
     *      description="Client updated"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid ID supplied",
     *     ),
     * )
     */
    public function update(ClientStoreRequest $request, int $id): JsonResponse|Response
    {
        /** @var Client $client */
        if(!$this->clientRepository->find($id)){
            return response(status: Response::HTTP_NOT_FOUND);
        }

        $this->clientRepository->update($id, [
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'phone_number' => $request->input('phoneNumber'),
            'email' => $request->input('email'),
        ]);

        return response()->noContent(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Delete client
     *
     * @OA\Delete(
     *     path="/api/client/{clientId}",
     *     tags={"Public API client"},
     *     operationId="destroyClient",
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         description="ID of customer that needs to be deleted",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="successful deleted client",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid ID supplied",
     *     ),
     * )
     */
    public function destroy(int $id): Response
    {
        /** @var Client $client */
        if(!$this->clientRepository->find($id)){
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }

        $this->clientRepository->delete($id);

        return response()->noContent(Response::HTTP_ACCEPTED);
    }
}
