<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Banner extends Model
{
    protected $table='banners';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','cat_id','banner_name','created_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



