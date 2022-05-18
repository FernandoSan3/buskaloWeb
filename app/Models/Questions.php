<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Questions extends Model
{
    protected $table='questions';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','sub_services_id','en_title','es_title','status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



