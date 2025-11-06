<?php

namespace App\Http\Controllers;

use App\Http\Requests\Asset\AttachOrDetachVulnerabilityRequest;
use App\Http\Requests\Asset\CreateAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Models\User;
use App\UseCases\Asset\AttachVulnerabilityUseCase;
use App\UseCases\Asset\CreateAssetUseCase;
use App\UseCases\Asset\DeleteAssetUseCase;
use App\UseCases\Asset\RiskCalculateUseCase;
use App\UseCases\Asset\ShowAssetUseCase;
use App\UseCases\Asset\UpdateAssetUseCase;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

#[Group('Assets')]
class AssetController extends Controller
{
    /** @operationId Create */
    public function create(CreateAssetRequest $request, CreateAssetUseCase $useCase): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $asset = $useCase->execute($request->getAssetDto(), $user);

        return response()->json(['id' => $asset->getKey()], Response::HTTP_CREATED);
    }

    /** @operationId Attach Vulnerability */
    public function attachVulnerability(
        int $assetId,
        AttachOrDetachVulnerabilityRequest $request,
        AttachVulnerabilityUseCase $useCase
    ): JsonResponse
    {
        $useCase->execute($assetId, $request->getCveId(), Auth::user()->id);

        return response()->json([
            'message' => 'Vulnerability attached',
            'asset_id' => $assetId,
            'cve_id' => $request->getCveId()
        ]);
    }

    /** @operationId Detach Vulnerability */
    public function detachVulnerability(
        int $assetId,
        AttachOrDetachVulnerabilityRequest $request,
        AttachVulnerabilityUseCase $useCase
    ): JsonResponse
    {
        $useCase->execute($assetId, $request->getCveId(), Auth::user()->id);

        return response()->json([
            'message' => 'Vulnerability detached',
            'asset_id' => $assetId,
            'cve_id' => $request->getCveId()
        ]);
    }

    /** @operationId Update */
    public function update(int $assetId, UpdateAssetRequest $request, UpdateAssetUseCase $useCase): JsonResponse
    {
        return response()->json(
            $useCase->execute($assetId, Auth::user()->id, $request->getAssetDto())
        );
    }

    /** @operationId Show */
    public function show(int $assetId, ShowAssetUseCase $useCase): JsonResponse
    {
        return response()->json($useCase->execute($assetId, Auth::user()->id));
    }

    /** @operationId Delete */
    public function delete(int $assetId, DeleteAssetUseCase $useCase): JsonResponse
    {
        $useCase->execute($assetId, Auth::user()->id);
        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    /** @operationId Calculate Risk */
    public function calculateRisk(int $assetId, RiskCalculateUseCase $useCase): JsonResponse
    {
        $data = $useCase->execute($assetId, Auth::user()->id);
        return response()->json([
            'asset_id' => $assetId,
            'calculated_risk' => $data->calculatedRisk,
            'max_cve_score' => $data->maxCveScore,
            'asset_weight_factor' => $data->assetWeightFactor,
            'risk_level' => $data->riskLevel,
            'calculation_timestamp' => $data->calculationTimestamp,
        ]);
    }
}
