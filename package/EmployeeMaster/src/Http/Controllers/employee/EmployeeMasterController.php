<?php

namespace Retailcore\EmployeeMaster\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\User;
use App\state;
use App\country;
use DB;
use Hash;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Retailcore\EmployeeMaster\Models\employee\employee_export;
use Retailcore\EmployeeMaster\Models\employee\employee_role;

class EmployeeMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $state                  =   state::all();
        $country                =   country::all();
        $company_id             =   Auth::user()->company_id;
        $roles                  =   employee_role::all();

        $result     =   user::where('company_id','=',$company_id)->where('is_master',0)
        ->with('state')
        ->with('employee_role')
        ->where('deleted_at','=',NULL)
        // ->whereNull('is_master')
        ->orderBy('user_id', 'DESC')
        ->get();

        return view('employee::employee/employee_master',compact('result','state','country','roles'));
    }

    public function employeeName_search(Request $request)
    {
       
        $result = user::select('employee_firstname','employee_middlename','employee_lastname','user_id')
        ->where('company_id',Auth::user()->company_id)
        ->Where('employee_firstname', 'LIKE', "%$request->search_val%")
        ->orWhere('employee_middlename', 'LIKE', "%$request->search_val%")
        ->orWhere('employee_lastname', 'LIKE', "%$request->search_val%")
        ->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result));
    }

    public function employee_mobile_search(Request $request)
    {
        $result = user::select('employee_mobileno','user_id')
        ->where('company_id',Auth::user()->company_id)
        ->orWhere('employee_mobileno', 'LIKE', "%$request->search_val%")
        ->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result));
    }

    public function employee_code_search(Request $request)
    {
        $result = user::select('employee_code','user_id')
        ->where('company_id',Auth::user()->company_id)
        ->Where('employee_code', 'LIKE', "%$request->search_val%")
        ->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result));
    }

    public function employee_designation_search(Request $request)
    {
        $result = user::select('employee_designation','user_id')
        ->where('company_id',Auth::user()->company_id)
        ->Where('employee_designation', 'LIKE', "%$request->search_val%")
        ->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result));
    }

    public function searchEmployeeResult(Request $request)
    {
        if($request->ajax())
        {
            $data                   =      $request->all();

            $employeeName           =      $data['employeeName'];
            $mobileNo               =      $data['mobileNo'];
            $empCode                =      $data['empCode'];
            $empDesignation         =      $data['empDesignation'];

            $query = user::where('company_id',Auth::user()->company_id)->where('deleted_at','=',NULL)->where('is_master',0);

            /////////// Employee Name Search Start
            if($employeeName!='')
            {
                $nameExplode        =   explode(' ',$employeeName);
                if(strpos($employeeName, ' ') !== false)
                {
                    
                    $query->whereRaw('employee_firstname LIKE "%'.$nameExplode[0].'%"');

                    if($nameExplode[1]!='')
                    {
                        $query->whereRaw('employee_middlename LIKE "%'.$nameExplode[1].'%"');
                    }
                    
                    if($nameExplode[2]!='')
                    {
                        $query->whereRaw('employee_lastname LIKE "%'.$nameExplode[2].'%"');
                    }
                }
                else
                {
                    $query->whereRaw('employee_firstname LIKE "%'.$employeeName.'%"')
                    ->orwhereRaw('employee_middlename LIKE "%'.$employeeName.'%"')
                    ->orwhereRaw('employee_lastname LIKE "%'.$employeeName.'%"');
                }
            }

            /////////// Employee Mobile Search Start
            if($mobileNo!='')
            {
                $query->whereRaw("employee_mobileno LIKE '%$mobileNo%'");
                
            }

            /////////// Employee Employee Code Search Start
            if($empCode!='')
            {
                $query->whereRaw("employee_code LIKE '%$empCode%'");
            }

            /////////// Employee Employee Designation Search Start
            if($empDesignation!='')
            {
                $query->whereRaw("employee_designation LIKE '%$empDesignation%'");
            }

            /////////// Employee status Search Start
            if($data['radioValue']!='')
            {
                $query->where('is_active','=',$data['radioValue']);
            }

            $query->with('state');

            $result     =   $query->orderBy('user_id', 'DESC')->get();
            // dd($result); exit;

            return view('employee::employee/view_employee_data',compact('result'))->render();
        }
    }

    public function employee_form_create(Request $request)
    {
        $employee_form_data = $request->all();    

        $validate_error = \Validator::make($employee_form_data,
        [
            'employee_firstname' => ['required'],
            'employee_middlename' => ['required'],
            'employee_lastname' => ['required'],
            'employee_mobileno' => ['required', Rule::unique('users')->ignore($employee_form_data['user_id_'], 'user_id')],
            'email' => ['required',Rule::unique('users')->ignore($employee_form_data['user_id_'], 'user_id')],
        ]);

        if($validate_error-> fails())
        {
            return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_error->messages()));
            exit;
        }

        if($employee_form_data['chkPicture']=='')
        {
            $validate_image_error = \Validator::make($employee_form_data,
            [
                'employee_picture' => ['mimes:jpeg,png,jpg,gif,svg|max:2048']
            ]);

            if($validate_image_error-> fails())
            {
                return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_image_error->messages()));
                exit;
            }   
        }   

        if($validate_error->passes())
        {
            if($request->file('employee_picture'))
            {
                $image  =   $request->file('employee_picture');
                $new_name = $employee_form_data['employee_firstname'].'_'.$employee_form_data['employee_middlename'].'_'.$employee_form_data['employee_lastname'].'_'.rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path(EMPLOYEE_IMAGE_URL_CONTROLLER), $new_name);
            }
            else
            {
                $new_name   =   '';
            }

            if($employee_form_data['chkPicture']!='')
            {
                $new_name   =   $employee_form_data['chkPicture'];
            }
            else
            {
                $new_name   =   $new_name;
            }

            $userId = Auth::User()->user_id;
            // print_r($userId); exit;
            $company_id = Auth::User()->company_id;

            $created_by = $userId;
            
            $employee_joiningdate   =   $employee_form_data['employee_joiningdate']==''?NULL:date('Y-m-d',strtotime($employee_form_data['employee_joiningdate']));
            $employee_dob           =   $employee_form_data['employee_dob']==''?NULL:date('Y-m-d',strtotime($employee_form_data['employee_dob']));
            $employee_resigned_date =   $employee_form_data['employee_resigned_date']==''?NULL:date('Y-m-d',strtotime($employee_form_data['employee_resigned_date']));

            if($employee_form_data['adminpassword']=='')
            {
                $password               =   $employee_form_data['password']==''?$employee_form_data['old_password_']:bcrypt($employee_form_data['password']);
                $encrypt_password       =   $employee_form_data['encrypt_password']==''?$employee_form_data['old_encrypt_password_']:bcrypt($employee_form_data['encrypt_password']);
                $admin                  =   0;
            }
            else
            {
                $password               =   $employee_form_data['password']==''?$employee_form_data['password']:bcrypt($employee_form_data['password']);
                $encrypt_password       =   $employee_form_data['encrypt_password']==''?$employee_form_data['encrypt_password']:bcrypt($employee_form_data['encrypt_password']);

                $admin      =   user::where('user_id',Auth::User()->user_id)->get();
                if(sizeof($admin)!=0)
                {
                    if ((Hash::check($employee_form_data['adminpassword'], $admin[0]->encrypt_password)))
                    { 
                        $admin  =   0;
                    }
                    else
                    {
                        $admin  =   1;
                    }
                }
                else
                {
                    $admin  =   1;
                }
            }

            if($admin==0)
            {
                $users = user::updateOrCreate(
                ['user_id' => $employee_form_data['user_id_'], 'company_id'=>$company_id,],
                [
                    'created_by' =>$company_id,
                    'is_master' => (isset($employee_form_data['is_master'])?$employee_form_data['is_master'] : ''),
                    'employee_code' => (isset($employee_form_data['employee_code'])?$employee_form_data['employee_code'] : ''),
                    'employee_firstname' => (isset($employee_form_data['employee_firstname'])?$employee_form_data['employee_firstname'] : ''),
                    'employee_middlename' => (isset($employee_form_data['employee_middlename'])?$employee_form_data['employee_middlename'] : ''),
                    'employee_lastname' => (isset($employee_form_data['employee_lastname'])?$employee_form_data['employee_lastname'] : ''),
                    'employee_mobileno_dial_code' => (isset($employee_form_data['employee_mobileno_dial_code'])?$employee_form_data['employee_mobileno_dial_code'] : ''),
                    'employee_mobileno' => (isset($employee_form_data['employee_mobileno'])?$employee_form_data['employee_mobileno'] : ''),
                    'employee_joiningdate' => ($employee_joiningdate),
                    'employee_login' => (isset($employee_form_data['employee_login'])?$employee_form_data['employee_login'] : ''),
                    'email' => (isset($employee_form_data['email'])?$employee_form_data['email'] : ''),
                    'password' => ($password),
                    'encrypt_password' => ($encrypt_password),
                    'employee_alternate_mobile_dial_code' => (isset($employee_form_data['employee_alternate_mobile_dial_code'])?$employee_form_data['employee_alternate_mobile_dial_code'] : ''),
                    'employee_alternate_mobile' => (isset($employee_form_data['employee_alternate_mobile'])?$employee_form_data['employee_alternate_mobile'] : ''),
                    'employee_family_member_mobile_dial_code' => (isset($employee_form_data['employee_family_member_mobile_dial_code'])?$employee_form_data['employee_family_member_mobile_dial_code'] : ''),
                    'employee_family_member_mobile' => (isset($employee_form_data['employee_family_member_mobile'])?$employee_form_data['employee_family_member_mobile'] : ''),
                    'employee_designation' => (isset($employee_form_data['employee_designation'])?$employee_form_data['employee_designation'] : ''),
                    'employee_duties' => (isset($employee_form_data['employee_duties'])?$employee_form_data['employee_duties'] : ''),
                    'employee_salary_offered' => (isset($employee_form_data['employee_salary_offered'])?$employee_form_data['employee_salary_offered'] : ''),
                    'employee_skills' => (isset($employee_form_data['employee_skills'])?$employee_form_data['employee_skills'] : ''),
                    'employee_education' => (isset($employee_form_data['employee_education'])?$employee_form_data['employee_education'] : ''),
                    'employee_past_experience' => (isset($employee_form_data['employee_past_experience'])?$employee_form_data['employee_past_experience'] : ''),
                    'employee_dob' => ($employee_dob),
                    'employee_marital_status' => (isset($employee_form_data['employee_marital_status'])?$employee_form_data['employee_marital_status'] : ''),
                    'employee_address_type' => (isset($employee_form_data['employee_address_type'])?$employee_form_data['employee_address_type'] : ''),
                    'employee_address' => (isset($employee_form_data['employee_address'])?$employee_form_data['employee_address'] : ''),
                    'employee_area' => (isset($employee_form_data['employee_area'])?$employee_form_data['employee_area'] : ''),
                    'employee_city_town' => (isset($employee_form_data['employee_city_town'])?$employee_form_data['employee_city_town'] : ''),
                    'state_id' => (isset($employee_form_data['state_id']) && $employee_form_data['state_id'] != ''? $employee_form_data['state_id'] : NULL),
                    'employee_zipcode' => (isset($employee_form_data['employee_zipcode'])?$employee_form_data['employee_zipcode'] : ''),
                    'country_id' => (isset($employee_form_data['country_id']) && $employee_form_data['country_id'] != ''? $employee_form_data['country_id'] : NULL),
                    'employee_reference' => (isset($employee_form_data['employee_reference'])?$employee_form_data['employee_reference'] : ''),
                    'employee_resigned_date' => ($employee_resigned_date),
                    'employee_resigned_reason' => (isset($employee_form_data['employee_resigned_reason'])?$employee_form_data['employee_resigned_reason'] : ''),
                    'employee_remarks' => (isset($employee_form_data['employee_remarks'])?$employee_form_data['employee_remarks'] : ''),
                    'employee_role_id' => (isset($employee_form_data['employee_role_id_'])?$employee_form_data['employee_role_id_'] : NULL),
                    'is_active' => '1',
                    'employee_picture' => $new_name,
                    'app_id' => md5(microtime().$employee_form_data['employee_firstname']),
                    'app_secret' => md5(microtime().$employee_form_data['email'])
                    
                ]);

                $user_id = $users->user_id;

                if($users)
                {
                    if ($employee_form_data['user_id'] != '')
                    {

                        return json_encode(array("Success"=>"True","Message"=>"Employee Account has been successfully updated.","url"=>"employee_master"));
                    }
                    else
                    {
                        return json_encode(array("Success"=>"True","Message"=>"Employee Account has been successfully added.","url"=>"employee_master"));
                    }
                }
                else
                {
                    return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
                }
            }
            else
            {
                return json_encode(array("Success"=>"False","Message"=>"Admin Password not matched"));
            }   // check admin
        }
    }

    public function changeStatus(Request $request)
    {
        $data       =   $request->all();
        $user_id    =   $data['user_id'];

        $status     =   user::select('is_active','user_id')->where('user_id','=',$user_id)->where('company_id','=',Auth::user()->company_id)->get();

        if($status[0]['is_active']==1)
        {
            $status     =   0;
            $class      =   'RedBackground';
        }
        else
        {
            $status     =   1;
            $class      =   'GreenBackground';
        }

        $query  =   user::where('user_id', $user_id)
        ->update([
           'is_active' => $status
        ]);

        return json_encode(array("Success"=>"False","status"=>$status,"user_id"=>$user_id,"class"=>$class));

    }

    public function removePicture(Request $request)
    {
        $data           =   $request->all();
        $employee_id    =   $data['user_id'];

        $result         =   user::select('employee_picture')->where('user_id',$employee_id)->get();
        $image_path     =   public_path(EMPLOYEE_IMAGE_URL_CONTROLLER).'/'.$result[0]->employee_picture;

        @unlink($image_path);   // REMOVE IMAGE FROM FOLDER
        
        $query  =   user::where('user_id', $employee_id)
        ->update([
           'modified_by' => Auth::User()->user_id,
           'updated_at' => date('Y-m-d H:i:s'),
           'employee_picture' => '',
        ]);

        return json_encode(array("Message"=>"employee picture removed successfully","picture"=>'empty'));
    }

    public function deleteEmployee(Request $request)
    {
        $data       =   $request->all();
        $employee_id    =   $data['user_id'];

        $query  =   user::where('user_id', $employee_id)
        ->update([
           'deleted_by' => Auth::User()->user_id,
           'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return json_encode(array("Message"=>"employee deleted successfully","url"=>"employee_master"));

    }

    public function editEmployee(Request $request)
    {
        $data       =   $request->all();
        $employee_id    =   $data['user_id'];

        $result     =   user::where('user_id',$employee_id)->get();

        return json_encode(array("Data"=>$result));

    }

    public function showResume(Request $request)
    {
        $data       =   $request->all();
        $employee_id    =   $data['user_id'];

        $result     =   user::where('user_id',$employee_id)->with('state','country','employee_role')->get();

        return json_encode(array("Data"=>$result));

    }

    public function changePassword(Request $request)
    {
        $data               =   $request->all();
        $employee_id        =   $data['user_id'];
        $old_password       =   $data['old_password'];
        $re_old_password    =   $data['re_old_password'];
        $new_password       =   $data['new_password'];
        $admin_password     =   $data['admin_password'];

        $admin      =   user::where('user_id',Auth::User()->user_id)->get();
        if(sizeof($admin)!=0)
        {
            if ((Hash::check($admin_password, $admin[0]->encrypt_password)))
            {    
                $employee   =   user::where('user_id',$employee_id)->get();
                if(sizeof($employee)!=0)
                {
                    if ((Hash::check($old_password, $employee[0]->encrypt_password)))
                    {
                        $query  =   user::where('user_id', $employee_id)
                        ->update([
                            'modified_by' => Auth::User()->user_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'password' => bcrypt($new_password),
                            'encrypt_password' => bcrypt($new_password),
                        ]);
                        return json_encode(array("Success"=>"True","Message"=>"employee password changed successfully...","url"=>"employee_master"));
                    }
                    else
                    {
                        return json_encode(array("Success"=>"False","Message"=>"employee old password does not matched"));
                    }
                }
            }
            else
            {
                return json_encode(array("Success"=>"False","Message"=>"admin password does not matched"));   
            }
        }
    }

    public function createPassword(Request $request)
    {
        $data               =   $request->all();
        $employee_id        =   $data['user_id'];
        $new_password       =   $data['new_password'];
        $confirm_password   =   $data['confirm_password'];
        $admin_password     =   $data['admin_password'];

        $admin      =   user::where('user_id',Auth::User()->user_id)->get();
        if(sizeof($admin)!=0)
        {
            if ((Hash::check($admin_password, $admin[0]->encrypt_password)))
            {
                $query  =   user::where('user_id', $employee_id)
                ->update([
                    'modified_by' => Auth::User()->user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'password' => bcrypt($new_password),
                    'encrypt_password' => bcrypt($new_password),
                    'employee_login' => 1,
                ]);
                return json_encode(array("Success"=>"True","Message"=>"employee password created successfully...","url"=>"employee_master"));
            }
            else
            {
                return json_encode(array("Success"=>"False","Message"=>"admin password does not matched"));
            }
        }
    }


    public function exportemployee_details(Request $request)
    {
        return Excel::download(new employee_export($request->employeeName,$request->mobileNo,$request->empCode,$request->empDesignation,$request->radioValue), 'Employee-Export.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\employee_master  $employee_master
     * @return \Illuminate\Http\Response
     */
    public function show(employee_master $employee_master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\employee_master  $employee_master
     * @return \Illuminate\Http\Response
     */
    public function edit(employee_master $employee_master)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\employee_master  $employee_master
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, employee_master $employee_master)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\employee_master  $employee_master
     * @return \Illuminate\Http\Response
     */
    public function destroy(employee_master $employee_master)
    {
        //
    }
}
