<?php

namespace App\Http\Requests\Asset;

use App\Dto\AssetDto;
use App\Enums\AssetCriticalityLevelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:4098',
            'device_type' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:255',
            'criticality_level' => ['sometimes', Rule::enum(AssetCriticalityLevelEnum::class)],
        ];
    }

    public function getAssetDto(): AssetDto
    {
        return new AssetDto(
            name: $this->validated('name'),
            description: $this->validated('description'),
            deviceType: $this->validated('device_type'),
            location: $this->validated('location'),
            criticalityLevel: $this->validated('criticality_level') ?
                AssetCriticalityLevelEnum::from($this->get('criticality_level')) : null,
        );
    }
}
