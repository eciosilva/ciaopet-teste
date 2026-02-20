<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $especies = ['Cachorro', 'Gato', 'Pássaro', 'Peixe', 'Hamster', 'Coelho'];
        $generos = ['Macho', 'Fêmea'];
        
        $racasPorEspecie = [
            'Cachorro' => ['Pastor Alemão', 'Golden Retriever', 'Bulldog', 'Labrador', 'Border Collie', 'Vira-lata'],
            'Gato' => ['Persa', 'Siamês', 'Maine Coon', 'British Shorthair', 'Ragdoll', 'Vira-lata'],
            'Pássaro' => ['Calopsita', 'Canário', 'Periquito', 'Bem-te-vi'],
            'Peixe' => ['Beta', 'Goldfish', 'Guppy', 'Neon'],
            'Hamster' => ['Sírio', 'Anão Russo', 'Chinês'],
            'Coelho' => ['Angorá', 'Mini Lop', 'Holandês']
        ];

        $especie = $this->faker->randomElement($especies);
        $raca = $this->faker->randomElement($racasPorEspecie[$especie]);

        return [
            'nome' => $this->faker->firstName(),
            'especie' => $especie,
            'raca' => $raca,
            'genero' => $this->faker->randomElement($generos),
            'data_nascimento' => $this->faker->dateTimeBetween('-10 years', '-1 month')->format('Y-m-d'),
            'peso' => $this->faker->randomFloat(2, 0.1, 50),
            'numero_microchip' => $this->faker->optional(0.7)->numerify('###############'), // 70% chance de ter microchip
            'tutor_id' => null, // Será definido no teste quando necessário
            'observacoes' => $this->faker->optional(0.8)->sentence(),
        ];
    }

    /**
     * Pet com tutor
     */
    public function withTutor(?User $tutor = null): static
    {
        return $this->state(fn (array $attributes) => [
            'tutor_id' => $tutor?->id ?? User::factory(),
        ]);
    }

    /**
     * Pet sem tutor (disponível para adoção)
     */
    public function withoutTutor(): static
    {
        return $this->state(fn (array $attributes) => [
            'tutor_id' => null,
        ]);
    }

    /**
     * Pet com microchip
     */
    public function withMicrochip(): static
    {
        return $this->state(fn (array $attributes) => [
            'numero_microchip' => $this->faker->numerify('###############'),
        ]);
    }

    /**
     * Pet sem microchip
     */
    public function withoutMicrochip(): static
    {
        return $this->state(fn (array $attributes) => [
            'numero_microchip' => null,
        ]);
    }

    /**
     * Pet cachorro
     */
    public function dog(): static
    {
        $racasCachorro = ['Pastor Alemão', 'Golden Retriever', 'Bulldog', 'Labrador', 'Border Collie', 'Vira-lata'];
        
        return $this->state(fn (array $attributes) => [
            'especie' => 'Cachorro',
            'raca' => $this->faker->randomElement($racasCachorro),
            'peso' => $this->faker->randomFloat(2, 5, 50),
        ]);
    }

    /**
     * Pet gato
     */
    public function cat(): static
    {
        $racasGato = ['Persa', 'Siamês', 'Maine Coon', 'British Shorthair', 'Ragdoll', 'Vira-lata'];
        
        return $this->state(fn (array $attributes) => [
            'especie' => 'Gato',
            'raca' => $this->faker->randomElement($racasGato),
            'peso' => $this->faker->randomFloat(2, 2, 10),
        ]);
    }
}
