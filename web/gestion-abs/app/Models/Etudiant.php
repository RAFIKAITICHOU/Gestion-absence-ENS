<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Etudiant extends Model
{
    use HasRoles;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cne',
        'groupe_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function groupe()
    // {
    //     return $this->belongsTo(Groupe::class);
    // }


    public function groupe()
    {
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }
    public function presences()
    {
        return $this->hasMany(\App\Models\Presence::class, 'id_etudiant');
    }
}

