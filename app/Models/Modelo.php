<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Modelo extends Model
{
    use HasFactory;

     protected $fillable = ['marca_id','nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules()
    {
        return [
            'marca_id' => 'exists:App\Models\Marca,id',
            'nome' => 'required|min:3', Rule::unique('name')->ignore($this->id),
            'imagem' => 'required|file|mimes:png, jpeg, jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
