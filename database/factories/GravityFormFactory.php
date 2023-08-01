<?php

namespace ReesMcIvor\GravityForms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ReesMcIvor\GravityForms\Models\GravityForm;

class GravityFormFactory extends Factory
{
    protected $model = GravityForm::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'fields' => $this->faker->array,
        ];
    }
}
