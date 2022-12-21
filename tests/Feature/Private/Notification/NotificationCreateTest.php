<?php

namespace Tests\Feature\Private\Notification;

use App\Enums\NotificationChannelEnum;
use App\Models\Client;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class NotificationCreateTest extends TestCase
{
    private string $routeTemplate = 'api/private/notification';

    public function testSuccess(): void
    {
        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->postJson(
            $this->routeTemplate,
            [
                [
                    'clientId' => $clientId = Client::factory()->create()->id,
                    'channel' => $channel = NotificationChannelEnum::EMAIL->value,
                    'content' => $content = str_repeat('-', 200),
                ],
            ],
            $this->getAuthHeader($token)
        )->assertStatus(201);

        /** @var Notification $record */
        $record = Notification::query()->where([
            'client_id' => $clientId,
            'channel' => $channel,
            'content' => $content,
        ])->first();

        $this->assertNotNull($record->completed_at);
    }

    public function testChannelSmsContentTooLongFail(): void
    {
        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->postJson(
            $this->routeTemplate,
            [
                [
                    'clientId' => Client::factory()->create()->id,
                    'channel' => NotificationChannelEnum::SMS->value,
                    'content' => str_repeat($this->faker->sentence, 141),
                ],
            ],
            $this->getAuthHeader($token)
        )->assertStatus(422)
            ->assertJsonFragment([
                'message' => '0.content max length is 140',
                'errors' => [
                    'content' => [
                        '0.content max length is 140',
                    ]
                ],
            ]);
    }

    public function testClientNotFoundFail(): void
    {
        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->postJson(
            $this->routeTemplate,
            [
                [
                    'clientId' => -1,
                    'channel' => NotificationChannelEnum::SMS->value,
                    'content' => str_repeat('-', 140),
                ],
            ],
            $this->getAuthHeader($token)
        )->assertStatus(422)
            ->assertJsonFragment([
                'message' => '0.clientId not found',
                'errors' => [
                    'clientId' => [
                        '0.clientId not found',
                    ]
                ],
            ]);
    }

    public function testIncorrectChannelFail(): void
    {
        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->postJson(
            $this->routeTemplate,
            [
                [
                    'clientId' => Client::factory()->create()->id,
                    'channel' => $this->faker->name,
                    'content' => str_repeat('-', 140),
                ],
            ],
            $this->getAuthHeader($token)
        )->assertStatus(422)
            ->assertJsonFragment([
                'message' => '0.channel value must be in: sms,email',
                'errors' => [
                    'channel' => [
                        '0.channel value must be in: sms,email',
                    ]
                ],
            ]);
    }

    public function testAuthorizationHeaderMissingFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->postJson($this->routeTemplate)->assertStatus(401);
    }

    public function testAuthorizationTokenWrongFail(): void
    {
        $this->mockPrivateAuthToken($this->faker->uuid);

        $this->postJson($this->routeTemplate, $this->getAuthHeader(''))->assertStatus(401);
    }
}
