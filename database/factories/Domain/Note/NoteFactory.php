<?php

namespace Database\Factories\Domain\Note;

use App\Domain\Note\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(),
            'content' => $this->faker->text(),
        ];
    }
}
