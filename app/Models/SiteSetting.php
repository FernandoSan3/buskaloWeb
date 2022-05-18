<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSetting extends Model
{
    protected $table='site_settings';
    protected $primaryKey = 'id';
    public $incrementing = TRUE;


    protected $fillable = ['id','logo','company_contact','company_address','	company_email','facebook','linkedin','twitter','google','copyright_text','footer_text','terms','disclaimer','created_at','updated_at','deleted_at'];

     protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}



