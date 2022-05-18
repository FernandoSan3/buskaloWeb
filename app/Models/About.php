<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class About extends Model
{
    protected $table='about_us';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','description','created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



