<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetCollection;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Services\PetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct(
        protected PetService $petService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): PetCollection
    {
        $pets = $this->petService->getPetsWithFilters($request);
        
        return new PetCollection($pets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request): JsonResponse
    {
        $pet = $this->petService->createPet($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pet criado com sucesso!',
            'data' => new PetResource($pet),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new PetResource($pet),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request, Pet $pet): JsonResponse
    {
        $updatedPet = $this->petService->updatePet($pet, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pet atualizado com sucesso!',
            'data' => new PetResource($updatedPet),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet): JsonResponse
    {
        $this->petService->deletePet($pet);

        return response()->json([
            'success' => true,
            'message' => 'Pet removido com sucesso!',
        ]);
    }

    /**
     * Get available options for form fields
     */
    public function options(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->petService->getFormOptions(),
        ]);
    }
}
