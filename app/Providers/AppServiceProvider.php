<?php

namespace App\Providers;

use App\home_navigation;
use App\home_navigations_data;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Hash;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Retailcore\EmployeeMaster\Models\employee\employee_role;
use Retailcore\EmployeeMaster\Models\employee\employee_role_permission;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Schema::defaultStringLength(191);
        
        $checkTbl   =   Schema::hasTable('home_navigations');

        if($checkTbl)
        {
            session(['ccompany_profile'=>1]);

            view()->composer('*', function ($view)
            {
                if(Auth::check())
                {
                    $user   =   user::select('employee_role_id','is_master')->where('user_id','=',Auth::User()->user_id)->get();

                    if($user[0]['is_master']==1)
                    {
                        $home_navigation = home_navigation::where('is_active','=','1')->orderBy('ordering', 'ASC')
                        ->with('home_navigations_data')->get();
                    }
                    else
                    {

                        $home_navigation    =   employee_role_permission::where('employee_role_id',$user[0]['employee_role_id'])
                            ->where('permission_view',1)
                            ->where('home_navigation_data_id',NULL)
                            ->with('home_navigations')
                            ->whereNull('deleted_by')
                            ->groupBy('home_navigation_id','home_navigation_data_id')
                            ->get();

                        foreach ($home_navigation as $key => $value)
                        {
                  
                             $home_navigation[$key]['sub'] = employee_role_permission::where('home_navigation_id',$value['home_navigation_id'])
                             ->where('home_navigation_data_id','!=',NULL)
                             ->where('employee_role_id',$user[0]['employee_role_id'])
                             ->where('permission_view',1)
                             ->with('home_navigations_data_s')
                             ->get();
                           
                        }
                    }

                    $navData = array(
                        'navLinks' => $home_navigation,
                        'chk_master' => $user[0]['is_master'],
                    );

                    view()->share('navLinks',$navData);
                }
           });

                        

            view()->composer('*', function ($view)
            {
                if(Auth::check())
                {
                    $current_url    =   url()->current();
                    $strArray       =   explode('/',$current_url);
                    $pageUrl        =   end($strArray);

                    $breadcrumb     =   home_navigations_data::select('home_navigation_id','home_navigation_data_id','nav_tab_display_name','nav_url')->where('nav_url','=',$pageUrl)
                    ->where('is_active','=','1')->with('home_navigation')->get();            

                    if(sizeof($breadcrumb)==0)
                    {
                        $navurl    =   '1'; //'dashboard';
                    }
                    else
                    {
                        $navurl    =   $breadcrumb[0]['home_navigation_data_id'];//$breadcrumb[0]['nav_url'];
                    }

                    $urlData = array(
                        'breadcrumb' => $breadcrumb,
                        'navurl' => $navurl,
                    );

                    view()->share('urlData',$urlData);

                    $employee_role_id     =   user::where('user_id','=',Auth::User()->user_id)->get();

                    $role_permissions     =   user::where('user_id','=',Auth::User()->user_id)
                    ->with([
                    'employee_role_permission' => function($fquery) use ($navurl,$employee_role_id) {
                        $fquery->select('*');
                        $fquery->where('home_navigation_id','=',$navurl);
                        $fquery->orwhere('home_navigation_data_id','=',$navurl);
                        $fquery->where('employee_role_id','=',$employee_role_id[0]['employee_role_id']);
                        $fquery->whereNull('deleted_by');
                    }
                    ])->get();

                    if($employee_role_id[0]['is_master']==1)
                    {
                        $permission_view        =   1;
                        $permission_add         =   1;
                        $permission_edit        =   1;
                        $permission_delete      =   1;
                        $permission_export      =   1;
                        $permission_print       =   1;
                        $permission_upload      =   1;
                    }
                    else
                    {
                        if(sizeof($role_permissions[0]['employee_role_permission'])==0)
                        {
                            $permission_view        =   0;
                            $permission_add         =   0;
                            $permission_edit        =   0;
                            $permission_delete      =   0;
                            $permission_export      =   0;
                            $permission_print       =   0;
                            $permission_upload      =   0;
                        }
                        else
                        {
                            $permission_view        =   $role_permissions[0]['employee_role_permission'][0]['permission_view'];
                            $permission_add         =   $role_permissions[0]['employee_role_permission'][0]['permission_add'];
                            $permission_edit        =   $role_permissions[0]['employee_role_permission'][0]['permission_edit'];
                            $permission_delete      =   $role_permissions[0]['employee_role_permission'][0]['permission_delete'];
                            $permission_export      =   $role_permissions[0]['employee_role_permission'][0]['permission_export'];
                            $permission_print       =   $role_permissions[0]['employee_role_permission'][0]['permission_print'];
                            $permission_upload      =   $role_permissions[0]['employee_role_permission'][0]['permission_upload'];
                        }
                    }
                    // print_r($permission_export); exit();
               
                    $role_permissions = array(
                        'permission_view' => $permission_view,
                        'permission_add' => $permission_add,
                        'permission_edit' => $permission_edit,
                        'permission_delete' => $permission_delete,
                        'permission_export' => $permission_export,
                        'permission_print' => $permission_print,
                        'permission_upload' => $permission_upload,
                    );

                    view()->share('role_permissions',$role_permissions);
                }
            });
            

        }

        view()->composer('*', function ($view)
        {
        
            $nav_type = company_profile::select('*')->get();

            if(Auth::check())
            {
                $view->with('currentUser', Auth::user());
            }
            else
            {
                $view->with('currentUser', null);
            }

            if(sizeof($nav_type)==0 )
            {
                $nav_type   =   [];
                view()->share('nav_type',$nav_type);
            }
            else
            {
                view()->share('nav_type',$nav_type);
            }
        });  
      
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
