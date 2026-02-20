<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pet extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nome',
        'especie',
        'raca',
        'genero',
        'data_nascimento',
        'peso',
        'numero_microchip',
        'observacoes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'peso' => 'decimal:2',
    ];

    /**
     * Enum values for genero field
     */
    public const GENEROS = [
        'Macho',
        'Fêmea',
        'Desconhecido',
    ];

    /**
     * Common species for validation/suggestions
     */
    public const ESPECIES_COMUNS = [
        'Cachorro',
        'Gato',
        'Pássaro',
        'Coelho',
        'Hamster',
        'Peixe',
        'Tartaruga',
    ];

    /**
     * Calculate pet age in years
     */
    public function getIdadeAttribute(): ?int
    {
        return $this->data_nascimento 
            ? $this->data_nascimento->diffInYears(Carbon::now())
            : null;
    }

    /**
     * Get formatted weight with unit
     */
    public function getPesoFormatadoAttribute(): ?string
    {
        return $this->peso ? "{$this->peso} kg" : null;
    }

    /**
     * Scope to filter by species
     */
    public function scopeByEspecie($query, $especie)
    {
        return $query->where('especie', $especie);
    }

    /**
     * Scope to filter by gender
     */
    public function scopeByGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }
}
