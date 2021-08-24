<?php

namespace Database\Factories;

use Domain\Teams\Models\TeamFoldersInvitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFoldersInvitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamFoldersInvitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'         => $this->faker->uuid,
            'folder_id'  => $this->faker->uuid,
            'email'      => $this->faker->email,
            'permission' => $this->faker->randomElement(['can-edit', 'can-view', 'can-view-and-download']),
            'status'     => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
