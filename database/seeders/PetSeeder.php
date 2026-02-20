<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only create pets if table is empty
        if (Pet::count() > 0) {
            $this->command->info('Tabela de pets já possui dados. Pulando PetSeeder.');
            return;
        }

        $pets = [
            [
                'nome' => 'Rex',
                'especie' => 'Cachorro',
                'raca' => 'Pastor Alemão',
                'genero' => 'Macho',
                'data_nascimento' => '2021-06-10',
                'peso' => 35.5,
                'numero_microchip' => '123456789012345',
                'tutor_id' => 1, // João Silva
                'observacoes' => 'Pet muito protetor e obediente. Adora brincar no parque.',
            ],
            [
                'nome' => 'Bella',
                'especie' => 'Gato', 
                'raca' => 'Persa',
                'genero' => 'Fêmea',
                'data_nascimento' => '2022-03-15',
                'peso' => 4.2,
                'numero_microchip' => '987654321098765',
                'tutor_id' => 2, // Maria Santos
                'observacoes' => 'Gata muito carinhosa e independente. Gosta de ficar no sol.',
            ],
            [
                'nome' => 'Max',
                'especie' => 'Cachorro',
                'raca' => 'Golden Retriever', 
                'genero' => 'Macho',
                'data_nascimento' => '2020-09-22',
                'peso' => 28.0,
                'numero_microchip' => '456789123456789',
                'tutor_id' => 1, // João Silva
                'observacoes' => 'Cachorro muito energético e brincalhão. Adora nadar.',
            ],
            [
                'nome' => 'Luna',
                'especie' => 'Gato',
                'raca' => 'Siamês',
                'genero' => 'Fêmea', 
                'data_nascimento' => '2023-01-08',
                'peso' => 3.8,
                'numero_microchip' => null,
                'tutor_id' => 2, // Maria Santos
                'observacoes' => 'Gata muito vocal e curiosa. Segue o tutor pela casa.',
            ],
            [
                'nome' => 'Thor',
                'especie' => 'Cachorro',
                'raca' => 'Rottweiler',
                'genero' => 'Macho',
                'data_nascimento' => '2019-11-30',
                'peso' => 45.2,
                'numero_microchip' => '789123456789123',
                'tutor_id' => null, // Sem tutor
                'observacoes' => 'Cachorro imponente mas dócil. Precisa de exercícios regulares.',
            ],
            [
                'nome' => 'Mimi',
                'especie' => 'Gato',
                'raca' => 'Vira-lata',
                'genero' => 'Fêmea',
                'data_nascimento' => '2021-07-14',
                'peso' => 3.5,
                'numero_microchip' => null,
                'tutor_id' => null, // Sem tutor
                'observacoes' => 'Gatinha resgatada. Muito carinhosa depois que pega confiança.',
            ],
            [
                'nome' => 'Buddy',
                'especie' => 'Cachorro',
                'raca' => 'Beagle',
                'genero' => 'Macho',
                'data_nascimento' => '2022-05-20',
                'peso' => 15.8,
                'numero_microchip' => '321654987321654',
                'tutor_id' => 1, // João Silva
                'observacoes' => 'Cachorro muito sociável e curioso. Adora farejar tudo.',
            ],
            [
                'nome' => 'Nala',
                'especie' => 'Gato',
                'raca' => 'Maine Coon',
                'genero' => 'Fêmea',
                'data_nascimento' => '2020-12-03',
                'peso' => 5.8,
                'numero_microchip' => '654321987654321',
                'tutor_id' => null, // Sem tutor
                'observacoes' => 'Gata grande e majestosa. Muito calma e elegante.',
            ],
            [
                'nome' => 'Charlie',
                'especie' => 'Pássaro',
                'raca' => 'Calopsita',
                'genero' => 'Macho',
                'data_nascimento' => '2023-04-12',
                'peso' => 0.09,
                'numero_microchip' => null,
                'tutor_id' => 2, // Maria Santos
                'observacoes' => 'Pássaro muito inteligente e falante. Sabe assobiar várias músicas.',
            ],
            [
                'nome' => 'Bolt',
                'especie' => 'Cachorro',
                'raca' => 'Border Collie',
                'genero' => 'Macho',
                'data_nascimento' => '2021-02-28',
                'peso' => 22.3,
                'numero_microchip' => '147258369147258',
                'tutor_id' => null, // Sem tutor
                'observacoes' => 'Cachorro extremamente inteligente e ágil. Ótimo para adestramento.',
            ],
        ];

        foreach ($pets as $petData) {
            Pet::create($petData);
        }
    }
}
