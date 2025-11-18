<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursSession extends Model
{
    use HasFactory;

    protected $table = 'cours_sessions';

    protected $fillable = [
        'id_cours',
        'id_professeur',
        'id_salle',
        'groupe_id',
        'date',
        'heure_debut',
        'heure_fin'
    ];

    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'id_professeur');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class, 'id_cours');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class, 'id_salle');
    }
    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_session');
    }
    
}
