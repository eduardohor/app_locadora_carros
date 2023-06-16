<?php

namespace App\Models;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
  use HasFactory;

  protected $fillable = ['nome', 'imagem'];

  public function rules()
  {
    return [
      'nome' => 'required|min:3|unique:marcas',
      'imagem' => 'required|file|mimes:png'
    ];
  }

  public function feedback()
  {
    return [
      'required' => 'O campo :attribute é obrigatório',
      'nome.unique' => 'O nome da marca já existe',
      'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
      'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo png'
    ];
  }

  public function modelos()
  {
    return $this->hasMany(Modelo::class);
  }
}
