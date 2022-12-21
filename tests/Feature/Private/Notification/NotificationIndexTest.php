<?php

namespace Tests\Feature\Private\Notification;

use App\Enums\NotificationChannelEnum;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class NotificationIndexTest extends TestCase
{
    private string $routeTemplate = 'api/private/notification';

    public function testSuccess(): void
    {
        $notifications = Notification::query()->limit(config('app.perPage'))->get();

        $expectedData = $notifications->map(function (Notification $notification) {
            return [
                'id' => $notification->id,
                'clientId' => $notification->client_id,
                'channel' => $notification->channel,
                'content' => $notification->content,
            ];
        })->all();

        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->get($this->routeTemplate, $this->getAuthHeader($token))
            ->assertJsonFragment([
                'count' => $notifications->count(),
                'data' => $expectedData,
            ])
            ->assertStatus(200);
    }

    public function testAuthorizationHeaderMissingFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->get($this->routeTemplate)->assertStatus(401);
    }

    public function testAuthorizationTokenWrongFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->get($this->routeTemplate, $this->getAuthHeader(''))
            ->assertStatus(401);
    }
}
