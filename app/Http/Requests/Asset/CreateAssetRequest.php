<?php

namespace App\Http\Requests\Asset;

use App\Dto\AssetDto;
use Illuminate\Foundation\Http\FormRequest;

class CreateAssetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable|string|max:4098',
            'device_type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    public function getAssetDto(): AssetDto
    {
        return new AssetDto(
            $this->get('name'),
            $this->get('description'),
            $this->get('device_type'),
            $this->get('location'),
        );
    }
}
