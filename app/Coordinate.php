<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coordinate extends Model
{
    protected $fillable = ['lat', 'lng', 'street_id', 'address'];

    protected $table = 'coordinates';
}
