<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'semestre'];

    public function coursSessions()
    {
        return $this->hasMany(CoursSession::class, 'id_cours');
    }
    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_professeur');
    }
}
