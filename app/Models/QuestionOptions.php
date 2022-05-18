<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class QuestionOptions extends Model
{
    protected $table='question_options';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','question_id','en_option','es_option','status','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



