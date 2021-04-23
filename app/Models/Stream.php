<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','temperature','etat_porte','etat_moteur',
        'etat_ventilateur','carte_code'
    ];

    protected $hidden = [
        'password',
    ];
}
