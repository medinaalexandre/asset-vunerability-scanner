<?php

namespace App\Http\Controllers;

use App\Http\Requests\Asset\CreateAssetRequest;
use App\Models\User;
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
}
