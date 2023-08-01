<?php

namespace ReesMcIvor\GravityForms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ReesMcIvor\GravityForms\Models\GravityFormEntry;

class GravityFormEntryFactory extends Factory
{
    protected string $model = GravityFormEntry::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'fields' => $this->faker->array,
        ];
    }
}
