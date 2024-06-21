<?php
namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->optional()->phoneNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
