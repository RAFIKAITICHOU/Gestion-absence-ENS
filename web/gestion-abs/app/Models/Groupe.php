<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model
{
    use HasFactory;

    protected $table = 'groupes';

    protected $fillable = ['nom_groupe', 'id_filiere'];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    public function coursSessions()
    {
        return $this->hasMany(CoursSession::class, 'groupe_id');
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }
    
}
