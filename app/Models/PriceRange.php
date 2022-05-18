<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class PriceRange extends Model
{
    protected $table='price_range';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','start_price','end_price','percentage','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



