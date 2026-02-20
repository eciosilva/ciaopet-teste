<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'especie' => $this->especie,
            'raca' => $this->raca,
            'genero' => $this->genero,
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'idade' => $this->idade,
            'peso' => $this->peso,
            'peso_formatado' => $this->peso_formatado,
            'numero_microchip' => $this->numero_microchip,
            'observacoes' => $this->observacoes,
            'tutor' => $this->when($this->tutor, [
                'id' => $this->tutor?->id,
                'name' => $this->tutor?->name,
                'email' => $this->tutor?->email,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Incluir deleted_at apenas se existir (para debug)
            $this->mergeWhen($this->deleted_at, [
                'deleted_at' => $this->deleted_at,
            ]),
        ];
    }
}
