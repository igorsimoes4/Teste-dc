<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word(),
            'preco' => $this->faker->randomFloat(2, 1, 1000), // preço entre 1.00 e 1000.00
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

