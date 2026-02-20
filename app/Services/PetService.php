<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PetService
{
    /**
     * Get pets with filters and pagination
     */
    public function getPetsWithFilters(Request $request): LengthAwarePaginator
    {
        $query = Pet::query();

        // Apply filters
        $this->applyFilters($query, $request);
        
        // Apply sorting
        $this->applySorting($query, $request);

        // Paginate results
        $perPage = min($request->get('per_page', 15), 100);
        
        return $query->paginate($perPage);
    }

    /**
     * Create a new pet
     */
    public function createPet(array $validatedData): Pet
    {
        return Pet::create($validatedData);
    }

    /**
     * Update an existing pet
     */
    public function updatePet(Pet $pet, array $validatedData): Pet
    {
        $pet->update($validatedData);
        
        return $pet->fresh();
    }

    /**
     * Delete a pet (soft delete)
     */
    public function deletePet(Pet $pet): bool
    {
        return $pet->delete();
    }

    /**
     * Get available options for forms
     */
    public function getFormOptions(): array
    {
        return [
            'generos' => Pet::GENEROS,
            'especies_comuns' => Pet::ESPECIES_COMUNS,
        ];
    }

    /**
     * Apply search and filters to the query
     */
    protected function applyFilters(Builder $query, Request $request): void
    {
        // Filter by species
        if ($request->filled('especie')) {
            $query->byEspecie($request->especie);
        }

        // Filter by gender
        if ($request->filled('genero')) {
            $query->byGenero($request->genero);
        }

        // Search in name, breed, and microchip
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('raca', 'LIKE', "%{$search}%")
                  ->orWhere('numero_microchip', 'LIKE', "%{$search}%");
            });
        }
    }

    /**
     * Apply sorting to the query
     */
    protected function applySorting(Builder $query, Request $request): void
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        // Validate sort field
        $allowedSortFields = ['nome', 'especie', 'created_at', 'data_nascimento'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // Default sorting
            $query->orderBy('created_at', 'desc');
        }
    }
}