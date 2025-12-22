<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserFollows>
 */
class UserFollowsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

    $followerId = User::inRandomOrder()->value('id');

    $followedId = User::inRandomOrder()
        ->where('id', '!=', $followerId)
        ->value('id');

    if (!$followedId) {
        return [];
    }

    return [
        'follower_id' => $followerId,
        'followed_id' => $followedId,
    ];
    }
}
