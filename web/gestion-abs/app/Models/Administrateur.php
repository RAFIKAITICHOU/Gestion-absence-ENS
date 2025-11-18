<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Administrateur extends Model
{
    use HasRoles;

    protected $fillable = ['user_id'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
