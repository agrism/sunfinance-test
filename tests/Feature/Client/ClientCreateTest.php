<?php

namespace Tests\Feature\Client;

use Tests\TestCase;

class ClientCreateTest extends TestCase
{
    use CreateUpdateDataProviderTrait;

    private string $routeTemplate = 'api/client';

    public function testSuccess(): void
    {
        $this->postJson($this->routeTemplate, [
            'firstName' => $firstName = $this->name(),
            'lastName' => $lastName = $this->name(),
            'email' => $email = $this->faker->email,
            'phoneNumber' => $phone = sprintf('+371%s', rand(10000000, 99999999)),
        ])->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone_number' => $phone,
        ]);
    }

    /**
     * @dataProvider creationUpdateDataProvider
     */
    public function testCreateValidation(?string $firstName, ?string $lastName, ?string $email, ?string $phoneNumber, array $expectedResponse): void
    {
        $this->postJson($this->routeTemplate, [
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
