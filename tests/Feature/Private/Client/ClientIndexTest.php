<?php

namespace Tests\Feature\Private\Client;

use App\Models\Client;
use Tests\TestCase;

class ClientIndexTest extends TestCase
{
    private string $routeTemplate = 'api/private/client';

    public function testSuccess(): void
    {
        $clients = Client::query()->limit(config('app.perPage'))->get();

        $expectedData = $clients->map(function (Client $client) {
            return [
                'id' => $client->id,
                'firstName' => $client->first_name,
                'lastName' => $client->last_name,
                'phoneNumber' => $client->phone_number,
                'email' => $client->email,
            ];
        })->all();

        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->get($this->routeTemplate, $this->getAuthHeader($token))
            ->assertJsonFragment([
                'count' => $clients->count(),
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

        $this->get($this->routeTemplate, $this->getAuthHeader(''))->assertStatus(401);
    }
}
