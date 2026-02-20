<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Pet::query();

        // Filtros opcionais
        if ($request->filled('especie')) {
            $query->byEspecie($request->especie);
        }

        if ($request->filled('genero')) {
            $query->byGenero($request->genero);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('raca', 'LIKE', "%{$search}%")
                  ->orWhere('numero_microchip', 'LIKE', "%{$search}%");
            });
        }

        // Ordenação
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if (in_array($sortBy, ['nome', 'especie', 'created_at', 'data_nascimento'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Paginação
        $perPage = min($request->get('per_page', 15), 100); // Max 100 items per page
        $pets = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pets->items(),
            'pagination' => [
                'current_page' => $pets->currentPage(),
                'per_page' => $pets->perPage(),
                'total' => $pets->total(),
                'last_page' => $pets->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raca' => 'nullable|string|max:255',
            'genero' => ['nullable', Rule::in(Pet::GENEROS)],
            'data_nascimento' => 'nullable|date|before_or_equal:today',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'numero_microchip' => 'nullable|string|max:255|unique:pets,numero_microchip',
            'observacoes' => 'nullable|string|max:5000',
        ]);

        $pet = Pet::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pet criado com sucesso!',
            'data' => $pet,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pet->id,
                'nome' => $pet->nome,
                'especie' => $pet->especie,
                'raca' => $pet->raca,
                'genero' => $pet->genero,
                'data_nascimento' => $pet->data_nascimento?->format('Y-m-d'),
                'idade' => $pet->idade,
                'peso' => $pet->peso,
                'peso_formatado' => $pet->peso_formatado,
                'numero_microchip' => $pet->numero_microchip,
                'observacoes' => $pet->observacoes,
                'created_at' => $pet->created_at,
                'updated_at' => $pet->updated_at,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raca' => 'nullable|string|max:255',
            'genero' => ['nullable', Rule::in(Pet::GENEROS)],
            'data_nascimento' => 'nullable|date|before_or_equal:today',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'numero_microchip' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pets', 'numero_microchip')->ignore($pet->id)
            ],
            'observacoes' => 'nullable|string|max:5000',
        ]);

        $pet->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pet atualizado com sucesso!',
            'data' => $pet->fresh(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet): JsonResponse
    {
        $pet->delete();

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
            'data' => [
                'generos' => Pet::GENEROS,
                'especies_comuns' => Pet::ESPECIES_COMUNS,
            ],
        ]);
    }
}
