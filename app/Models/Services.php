<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Services extends Model
{
    protected $table='services';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','en_name','es_name','status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



