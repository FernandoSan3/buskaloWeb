<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Settings extends Model
{
    protected $table='settings';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

 
    protected $fillable = ['id', 'user_id', 'notification_sound', 'notification_preview', 'email_notification', 'app_language', 'created'];

     protected $dates = [
        'created',
         ];
}



