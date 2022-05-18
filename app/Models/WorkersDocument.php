<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class WorkersDocument extends Model
{
    protected $table='workers_document';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id', 'user_id', 'document_id', 'doc_name', 'status', 'created_at', 'updated_at', 'deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



