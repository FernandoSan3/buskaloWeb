<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class MobileSession extends Model
{
    protected $table='mobile_session';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','user_id','session_key','modified','created'];

     protected $dates = [
        'created',
        'modified',
    ];
}



