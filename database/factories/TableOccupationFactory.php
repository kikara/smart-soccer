<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TableOccupation>
 */
class TableOccupationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $dateTime = new \DateTimeImmutable();
        return [
            'start_game' => $dateTime->format('Y-m-d H:i:s'),
            'end_game' => $dateTime->modify('+35 minutes')->format('Y-m-d H:i:s')
        ];
    }
}
