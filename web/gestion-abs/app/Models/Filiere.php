<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $fillable = ['nom_filiere', 'description'];

    public function groupes()
    {
        return $this->hasMany(Groupe::class, 'id_filiere');
    }
}
