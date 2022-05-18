<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Areatype extends Model
{
    protected $table='area_type';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','area_type_name','price_percent','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



