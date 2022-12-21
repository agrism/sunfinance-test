<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationsStoreRequest;
use App\Http\Resources\NotificationCollectionResource;
use App\Http\Resources\NotificationResource;
use App\Jobs\SendNotificationJob;
use App\Repositories\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @class NotificationController
 * @package App\Http\Controllers\Private
 */
class NotificationController extends Controller
{
    public function __construct(readonly private NotificationRepositoryInterface $notificationRepository)
    {
    }

    /**
     * List notifications
     *
     * @OA\Get(
     *     path="/api/private/notification",
     *     tags={"Private API notification"},
     *     operationId="privateListNotifications",
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
     *     @OA\Parameter(
     *         name="clientId",
     *         in="query",
     *         description="filter by clientid",
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
     *             ref="#/components/schemas/NotificationCollectionResource"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $records = $this->notificationRepository->paginate(config('app.perPage'), $request->query('page'), $request->query('clientId'));

        return response()->json(new NotificationCollectionResource($records), Response::HTTP_OK);
    }


    /**
     * Store new notification
     *
     * @OA\Post(
     *     path="/api/private/notification",
     *     tags={"Private API notification"},
     *     operationId="privateStoreNotifications",
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(ref="#/components/requestBodies/NotificationsStoreRequest"),
     *     @OA\Response(
     *      response=422,
     *      ref="#/components/responses/validationErrorResponse422"
     *     ),
     *     @OA\Response(
     *      response=201,
     *      description="Record created"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized",
     *     )
     * )
     */
    public function store(NotificationsStoreRequest $request): Response
    {
        $notifications = $request->all();
        $mappedNotifications = array_map(function (array $notificationAttributes) {
            return [
                'client_id' => data_get($notificationAttributes, 'clientId'),
                'channel' => data_get($notificationAttributes, 'channel'),
                'content' => data_get($notificationAttributes, 'content'),
            ];
        }, $notifications);

        SendNotificationJob::dispatch($this->notificationRepository->createNotifications($mappedNotifications));

        return response()->noContent(Response::HTTP_CREATED);
    }

    /**
     * Show notification
     *
     * @OA\Get(
     *     path="/api/private/notification/{notificationId}",
     *     tags={"Private API notification"},
     *     operationId="privateShowNotification",
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="notificationId",
     *         in="path",
     *         description="ID of notification that needs to show",
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
     *             ref="#/components/schemas/NotificationResource"
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
    public function show(int $id): Response
    {
        if (!$record = $this->notificationRepository->find($id)) {
            return response()->noContent(Response::HTTP_NOT_FOUND);
        }
        return response(new NotificationResource($record), Response::HTTP_OK);
    }
}
