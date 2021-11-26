<?php

namespace Database\Factories;

use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFolderInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamFolderInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'         => $this->faker->uuid,
            'parent_id'  => $this->faker->uuid,
            'inviter_id' => $this->faker->uuid,
            'email'      => $this->faker->email,
            'permission' => $this->faker->randomElement(['can-edit', 'can-view']),
            'status'     => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
