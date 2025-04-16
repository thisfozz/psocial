<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model{
    protected $table = 'cities';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'country_id',
    ];
}