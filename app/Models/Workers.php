<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Workers extends Model
{
    protected $table='workers';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id', 'user_id', 'email', 'password', 'first_name', 'last_name', 'profile_pic', 'mobile_number', 'address', 'status', 'created_at', 'updated_at', 'deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



