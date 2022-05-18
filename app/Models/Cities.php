<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Cities extends Model
{
    protected $table='cities';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','name','created_at','status','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



