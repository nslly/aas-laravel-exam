<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'name',
        'reports_to'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


}
