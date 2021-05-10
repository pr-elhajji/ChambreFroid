<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\DetailsLot;


class Lot extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'numero',
        'capacite-max',
        'quantite',
        'image',
        'chambre_id',
        'variete_id',
        'date-entree',
        'date-sortie'
    ];

}
