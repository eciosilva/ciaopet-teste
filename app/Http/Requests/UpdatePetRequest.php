<?php

namespace App\Http\Requests;

use App\Models\Pet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sem autenticação por enquanto
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $petId = $this->route('pet')?->id;

        return [
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
                Rule::unique('pets', 'numero_microchip')->ignore($petId)
            ],
            'observacoes' => 'nullable|string|max:5000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do pet é obrigatório.',
            'nome.string' => 'O nome deve ser um texto válido.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',
            
            'especie.required' => 'A espécie do pet é obrigatória.',
            'especie.string' => 'A espécie deve ser um texto válido.',
            'especie.max' => 'A espécie não pode ter mais de 255 caracteres.',
            
            'raca.string' => 'A raça deve ser um texto válido.',
            'raca.max' => 'A raça não pode ter mais de 255 caracteres.',
            
            'genero.in' => 'O gênero deve ser: ' . implode(', ', Pet::GENEROS),
            
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before_or_equal' => 'A data de nascimento não pode ser futura.',
            
            'peso.numeric' => 'O peso deve ser um número válido.',
            'peso.min' => 'O peso não pode ser negativo.',
            'peso.max' => 'O peso não pode exceder 999,99 kg.',
            
            'numero_microchip.string' => 'O número do microchip deve ser um texto válido.',
            'numero_microchip.max' => 'O número do microchip não pode ter mais de 255 caracteres.',
            'numero_microchip.unique' => 'Este número de microchip já está cadastrado.',
            
            'observacoes.string' => 'As observações devem ser um texto válido.',
            'observacoes.max' => 'As observações não podem ter mais de 5000 caracteres.',
        ];
    }
}
