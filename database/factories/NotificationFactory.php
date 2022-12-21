<?php

namespace Database\Factories;

use App\Enums\NotificationChannelEnum;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => data_get(($this->states->last()()),'client_id') ?? Client::factory()->create(),
            'channel' => NotificationChannelEnum::EMAIL->value,
            'content' => $this->faker->sentence,
            'completed_at' => null,
        ];
    }
}
