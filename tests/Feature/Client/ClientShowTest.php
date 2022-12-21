<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use Tests\TestCase;

class ClientShowTest extends TestCase
{
    private string $routeTemplate = 'api/client/%s';

    public function testSuccess1(): void
    {
        /** @var Client $client */
        $client = Client::factory([
            'phone_number' => sprintf('+371%s', rand(10000000, 99999999)),
        ])->create();

        $this->get(sprintf($this->routeTemplate, $client->id))
            ->assertJsonFragment([
                'firstName' => $client->first_name,
                'lastName' => $client->last_name,
                'phoneNumber' => $client->phone_number,
                'email' => $client->email,
            ])
            ->assertStatus(200);
    }


    public function testRecordNotFoundFail(): void
    {
        /** @var Client $client */

        $this->get(sprintf($this->routeTemplate, -1))->assertStatus(404);
    }

}
