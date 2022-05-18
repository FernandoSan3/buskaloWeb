<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Subservices extends Model
{
    protected $table='sub_services';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','services_id','en_name','es_name', 'image', 'price', 'percentage', 'quantity', 'status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



