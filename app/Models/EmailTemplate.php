<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    protected $table='email_template';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;

    protected $fillable = ['id','slug','mail_content','status','created_at','updated_at'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}



