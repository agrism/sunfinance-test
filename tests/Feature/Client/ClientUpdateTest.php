<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use Tests\TestCase;

class ClientUpdateTest extends TestCase
{
    use CreateUpdateDataProviderTrait;

    private string $routeTemplate = 'api/client/%s';

    public function testSuccess(): void
    {
        $client = Client::factory([
            'first_name' => $this->name(),
            'last_name' => $this->name(),
        ])->create();

        $this->putJson(sprintf($this->routeTemplate, $client->id), [
            'firstName' => $firstName = $this->name(),
            'lastName' => $lastName = $this->name(),
            'email' => $email = $this->faker->email,
            'phoneNumber' => $phone = sprintf('+371%s', rand(10000000, 99999999)),
        ])->assertStatus(204);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phone,
        ]);
    }

    /**
     * @dataProvider creationUpdateDataProvider
     */
    public function testUpdateValidation(?string $firstName, ?string $lastName, ?string $email, ?string $phoneNumber, array $expectedResponse): void
    {
        $this->putJson($this->routeTemplate, [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
        ])
            ->assertJsonFragment($expectedResponse)
            ->assertStatus(422);
    }

    private function name(): string
    {
        return preg_replace('/[^A-Za-z ]/', '', sprintf('%s ups', $this->faker->firstName));
    }
}
