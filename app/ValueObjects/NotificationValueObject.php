<?php

namespace App\ValueObjects;

/**
 * @OA\Schema()
 */
class NotificationValueObject
{
    /**
     * The notification id
     * @var int
     *
     * @OA\Property(
     *   property="id",
     *   type="int",
     *   description="notification id",
     *   example="1"
     * )
     */
    public readonly int $d;
    /**
     * The customer id
     * @var int
     *
     * @OA\Property(
     *   property="clientId",
     *   type="int",
     *   description="client id",
     *   example="1"
     * )
     */
    public readonly int $clientId;

    /**
     * Channel
     * @var string
     *
     * @OA\Property(
     *   property="channel",
     *   type="string",
     *   description="channel",
     *   example="sms"
     * )
     */
    public readonly string $channel;

    /**
     * Channel
     * @var string
     *
     * @OA\Property(
     *   property="content",
     *   type="string",
     *   description="content",
     *   example="some content"
     * )
     */
    public readonly string $content;

    /**
     * @param int $clientId
     * @param string $channel
     * @param string $content
     */
    public function __construct(int $id, int $clientId, string $channel, string $content)
    {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->channel = $channel;
        $this->content = $content;
    }
}
