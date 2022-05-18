<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkWithUs extends Model
{
    protected $table='work_with_us';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','description_cons', 'description_comp','created_at','updated_at','deleted_at'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



