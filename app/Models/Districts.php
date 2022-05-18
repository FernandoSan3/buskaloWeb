<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Districts extends Model
{
    protected $table='districts';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','city_id','name','status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



