<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class ChildSubservices extends Model
{
    protected $table='child_sub_services';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','sub_services_id','en_name','es_name','status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



