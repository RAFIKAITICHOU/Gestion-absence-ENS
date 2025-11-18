<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = ['etudiant_id', 'date', 'justifie'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
