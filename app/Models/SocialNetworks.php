<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class SocialNetworks extends Model
{
    protected $table='social_networks';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id', 'user_id', 'facebook_url', 'instagram_url', 'snap_chat_url', 'twitter_url', 'youtube_url', 'status', 'created_at', 'updated_at', 'deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



