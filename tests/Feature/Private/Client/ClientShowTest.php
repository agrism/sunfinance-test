<?php

namespace Tests\Feature\Private\Client;

use App\Models\Client;
use Tests\TestCase;

class ClientShowTest extends TestCase
{
    private string $routeTemplate = 'api/private/client/%s';

    public function testSuccess(): void
    {
        /** @var Client $client */
        $client = Client::factory([
            'phone_number' => sprintf('+371%s', rand(10000000, 99999999)),
        ])->create();

        $this->mockPrivateAuthToken($token = $this->faker->uuid);

        $this->get(sprintf($this->routeTemplate, $client->id), $this->getAuthHeader($token))
            ->assertJsonFragment([
                'id' => $client->id,
                'firstName' => $client->first_name,
                'lastName' => $client->last_name,
                'phoneNumber' => $client->phone_number,
                'email' => $client->email,
            ])
            ->assertStatus(200);
    }

    public function testRecordNotFoundFailFail(): void
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
