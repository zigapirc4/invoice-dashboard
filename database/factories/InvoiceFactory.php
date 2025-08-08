<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'invoice_number' => $this->faker->unique()->numerify('INV-#####'),
            'date' => $this->faker->date(),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => \App\Enums\InvoiceStatus::Draft->value,
            'description' => $this->faker->optional()->sentence(),
            'sent_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
