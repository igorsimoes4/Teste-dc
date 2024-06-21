<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;
    protected $fillable = ['venda_id', 'data_vencimento', 'valor'];

    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
}
