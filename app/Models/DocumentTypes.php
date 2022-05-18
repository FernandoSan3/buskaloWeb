<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class DocumentTypes extends Model
{
    protected $table='document_types';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id', 'type_en', 'type_es', 'status', 'created_at', 'updated_at', 'deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



