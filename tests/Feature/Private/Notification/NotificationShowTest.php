<?php

namespace Tests\Feature\Private\Notification;

use App\Enums\NotificationChannelEnum;
use App\Models\Client;
use App\Models\Notification;
use Tests\TestCase;

class NotificationShowTest extends TestCase
{
    private string $routeTemplate = 'api/private/notification/%s';

    public function testSuccess(): void
    {
        /** @var Notification $notification */
        $notification = Notification::factory([
            'client_id' => Client::factory()->create()->id,
            'channel' => NotificationChannelEnum::SMS->value,
            'content' => substr($this->faker->sentence, 0, 140),
        ])->create();

        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->get(sprintf($this->routeTemplate, $notification->id), $this->getAuthHeader($token))
            ->assertJsonFragment([
                'id' => $notification->id,
                'clientId' => $notification->client_id,
                'channel' => $notification->channel,
                'content' => $notification->content,
            ])
            ->assertStatus(200);
    }

    public function testRecordNotFoundFail(): void
    {
        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->get(sprintf($this->routeTemplate, -1), $this->getAuthHeader($token))
            ->assertStatus(404);
    }

    public function testAuthorizationHeaderMissingFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->get(sprintf($this->routeTemplate, -1))->assertStatus(401);
    }

    public function testAuthorizationTokenWrongFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->get(sprintf($this->routeTemplate, -1), $this->getAuthHeader(''))
            ->assertStatus(401);
    }
}
