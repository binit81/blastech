<?php

namespace Retailcore\EmployeeMaster\Models\employee;

use Illuminate\Database\Eloquent\Model;

class employee_role extends Model
{
    protected $primaryKey = 'employee_role_id'; //Default: id
    protected $guarded=['employee_role_id'];

    public function employee_role_permission()
    {
        return $this->hasOne('Retailcore\EmployeeMaster\Models\employee\employee_role_permission','employee_role_id','employee_role_id');
    }

}
