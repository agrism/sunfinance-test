<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use Tests\TestCase;

class ClientDeleteTest extends TestCase
{
    private string $routeTemplate = 'api/client/%s';

    public function testSuccess(): void
    {
        /** @var Client $client */
        $client = Client::factory()->create();

        $this->delete(sprintf($this->routeTemplate, $client->id))->assertStatus(202);
    }

    public function testRecordNotFoundFail(): void
    {
        /** @var Client $client */
        $this->delete(sprintf($this->routeTemplate, -1))->assertStatus(404);
    }

}
