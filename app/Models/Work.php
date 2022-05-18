<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    protected $table='how_it_is_work';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','search','search_descriptiom','compare','compare_description','hire','hire_description','image','description','created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



