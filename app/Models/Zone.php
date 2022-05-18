<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Zone extends Model
{
    protected $table='zone';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','title','address','center_lat','center_long','latlng','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



