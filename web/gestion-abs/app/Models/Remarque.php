<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remarque extends Model
{
    use HasFactory;

    protected $fillable = [
        'remarque',
        'bonus',
        'id_presence',
    ];

    // 🔁 Relation inverse : une remarque appartient à une présence
    public function presence()
    {
        return $this->belongsTo(Presence::class, 'id_presence');
    }
}
