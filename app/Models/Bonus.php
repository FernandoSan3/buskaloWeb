<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
   
class Bonus extends Model
{
    protected $table='bonus';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id', 'user_id', 'transaction_date', 'debit', 'credit', 'expire_status', 'current_balance', 'updated_at', 'deleted_at'];

     protected $dates = [
        'transaction_date',
        'updated_at',
        'deleted_at',
    ];
}



