<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contactus extends Model
{
    protected $table='contact_us';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','name','email','contact_number','description','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



