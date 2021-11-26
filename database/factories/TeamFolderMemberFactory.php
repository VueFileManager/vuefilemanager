<?php

namespace Database\Factories;

use Domain\Teams\Models\TeamFolderMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFolderMemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamFolderMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id'  => $this->faker->uuid,
            'user_id'    => $this->faker->uuid,
            'permission' => $this->faker->randomElement(['can-edit', 'can-view', 'owner']),
        ];
    }
}
