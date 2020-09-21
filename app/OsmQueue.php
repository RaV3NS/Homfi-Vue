<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OsmQueue extends Model
{
    protected $fillable = [
        'uuid',
        'lat',
        'lng',
    ];

    protected $table = 'osm_queue';
}
