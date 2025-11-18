<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Traits\HasRoles;

class Professeur extends Model
{
    use HasRoles;
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function coursSessions()
    {
        return $this->hasMany(CoursSession::class, 'id_professeur');
    }
}
