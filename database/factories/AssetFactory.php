<?php

namespace Database\Factories;

use App\Enums\AssetCriticalityLevelEnum;
use App\Enums\VulnerabilitySeverityEnum;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'device_type' => $this->faker->safari(),
            'location' => $this->faker->city(),
            'status' => 'initial',
            'user_id' => User::factory(),
            'criticality_level' => $this->faker->randomElement(AssetCriticalityLevelEnum::cases())
        ];
    }
}
