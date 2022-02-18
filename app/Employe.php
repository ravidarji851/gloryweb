<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employe extends Model
{
    use SoftDeletes;
    protected $table ='tbl_employe';
    protected $guarded = ['id'];

    public function setEmailAddressAttribute($value){
        $this->attributes['email_address'] = strtolower($value);
    }

    public function get_company(){
        return $this->belongsTo('App\Company','company_id');

    }
}
