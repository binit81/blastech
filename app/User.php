<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'user_id'; //Default: id
    protected $guarded  = ['user_id'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['employee_firstname','email','password','company_id','encrypt_password','api_token','is_active','user_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      //  'password', 'remember_token',
        'password',
    ];

    public function barcode_template()
    {
        return $this->hasOne('Retailcore\BarcodePrinting\Models\barcodeprinting\barcode_template','barcode_template_id','barcode_template_id');
    }

    public function state()
    {
        return $this->hasOne('App\state','state_id','state_id');
    }
    public function country()
    {
        return $this->hasOne('App\country','country_id','country_id');
    }

    public function employee_role()
    {
        return $this->hasOne('Retailcore\EmployeeMaster\Models\employee\employee_role','employee_role_id','employee_role_id');
    }

    public function employee_role_permission()
    {
        return $this->hasMany('Retailcore\EmployeeMaster\Models\employee\employee_role_permission','employee_role_id','employee_role_id');
    }
}
