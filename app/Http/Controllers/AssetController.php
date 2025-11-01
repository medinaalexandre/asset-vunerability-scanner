<?php

namespace App\Http\Controllers;

use App\Http\Requests\Asset\AttachVulnerabilityRequest;
use App\Http\Requests\Asset\CreateAssetRequest;
use App\Models\User;
use App\UseCases\Asset\AttachVulnerabilityUseCase;
use App\UseCases\Asset\CreateAssetUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AssetController extends Controller
{
    public function create(CreateAssetRequest $request, CreateAssetUseCase $useCase): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $asset = $useCase->execute($request->getAssetDto(), $user);

        return response()->json(['id' => $asset->getKey()], Response::HTTP_CREATED);
    }

    public function attachVulnerability(
        int $assetId,
        AttachVulnerabilityRequest $request,
        AttachVulnerabilityUseCase $useCase
    ): JsonResponse
    {
        $useCase->execute($assetId, $request->getVulnerabilityId());

        return response()->json([
            'message' => 'Vulnerability attached',
            'asset_id' => $assetId,
            'vulnerability_id' => $request->getVulnerabilityId()
        ]);
    }
}
