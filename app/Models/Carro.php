<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id','placa', 'disponivel', 'km'];

    public function rules()
    {
        return [
            'modelo_id' => 'exists:App\Models\Modelo,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required'
        ];
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
