<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class UserDevices extends Model
{
    protected $table='user_devices';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','user_id','device_id','device_type','created'];

     protected $dates = [
        'created',
    ];
}



