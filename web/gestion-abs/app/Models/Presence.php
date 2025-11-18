<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'etat',
        'retard',
        'id_etudiant',
        'id_session',
        'remarque',
        'bonus',
        'justification',
        'justificatif_fichier'
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    public function seance()
    {
        return $this->belongsTo(CoursSession::class, 'id_session');
    }
    public function session()
    {
        return $this->belongsTo(CoursSession::class, 'id_session');
    }
    public function remarque()
    {
        return $this->hasOne(Remarque::class, 'id_presence');
    }
}
