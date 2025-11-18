<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salle extends Model
{
    //use HasFactory;

    // protected $fillable = ['nom'];

    // public function coursSessions()
    // {
    //     return $this->hasMany(CoursSession::class, 'id_salle');
    // }

    use HasFactory;

    protected $fillable = ['nom', 'equipements', 'projecteurs'];

    public function coursSessions()
    {
        return $this->hasMany(CoursSession::class, 'id_salle');
    }

}
