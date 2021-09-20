<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\state;
use App\country;
use App\home_navigation;
use App\home_navigations_data;
use Retailcore\CreditBalance\Models\customer_creditaccount;
use Retailcore\Sales\Models\payment_method;
use Retailcore\Sales\Models\sales_bill;
use Retailcore\SalesReturn\Models\return_bill;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Hash;
use App\User;
use DB;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //return view('master');



        $userId             =   Auth::User()->user_id;
        $users      =   user::select('*')->where('user_id',$userId)->whereNull('deleted_at')->get();

        $state = state::all();
        $country = country::all();
        $company_profile = company_profile::where('company_id',Auth::user()->company_id)->first();

        $payment_methods = payment_method::where('is_active','=','1')->orderBy('payment_order','ASC')->get();

         $last_invoice_id = sales_bill::select('sales_bill_id')->where('company_id',Auth::user()->company_id)->orderBy('sales_bill_id', 'desc')->first();

        $f1     =    (date('m')<'04') ? date('y',strtotime('-1 year')) : date('y');
        $f2     =    (date('m')>'03') ? date('y',strtotime('+1 year')) : date('y');

        if($last_invoice_id == '')
        {
            $last_invoice_id = 1;
        }
        else
        {
            $last_invoice_id = $last_invoice_id['sales_bill_id']  + 1;
        }


        $invoiceno          =       $last_invoice_id.'/'.$f1.'-'.$f2;
        $chargeslist      =   product::select('product_id','product_name')
            ->where('company_id',Auth::user()->company_id)
            ->where('item_type','=',2)
            ->get();

        if($company_profile != ''  && $company_profile != null && $company_profile['company_name'] != '')
            {
                $billtype    =        $company_profile['billtype'];
                $billprefix  =        $company_profile['bill_number_prefix'];

                if($billprefix != '' || $billprefix!= null)
                {
                    $invoiceno          =       $billprefix.$invoiceno; 
                }
                else
                {
                  $invoiceno           =       $invoiceno; 
                }

                 session(['ccompany_profile'=>1]);
                 $company_profile    =    session('ccompany_profile');


                return view('dashboard',compact('users'));
            }
            else
            {
                 session(['ccompany_profile'=>0]);
                 $company_profile    =    session('ccompany_profile');

                return view('company_profile::company_profile/company_profile',compact('company_profile','state','country','users'));
            }

    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }

    public function dashboard()
    {
        $userId             =   Auth::User()->user_id;
        $company_id         =   Auth::user()->company_id;

        $state = state::all();
        $country = country::all();  

        ////CHECK FOR COMPANY PROFILE
        $company_profile = company_profile::where('company_id',Auth::user()->company_id)->first();


        if($company_profile == ''  || $company_profile == null )
        {
            session(['ccompany_profile'=>0]);
            $company_profile    =    session('ccompany_profile');

            return view('company_profile::company_profile/company_profile',compact('company_profile','state','country'));
        }
        else {


            ///END OF CHECK COMPANY PROFILE


            //////////////////////////// TODAY SALES /////////////////////////////

            $today = date('Y-m-d');

            $todaySales = sales_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$today' and '$today'")
                // ->whereRaw("Date(sales_bills.created_at) between '$today' and '$today'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $todayReturn = return_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$today' and '$today'")
                // ->whereRaw("Date(return_bills.created_at) between '$today' and '$today'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $finalTodaySales = $todaySales - $todayReturn;

            /////////////////////////// MONTH SALES //////////////////////////////

            $firstDay = date('Y-m-01', strtotime($today));
            $lastDay = date('Y-m-t', strtotime($today));
             
            $monthSales = sales_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$firstDay' and '$lastDay'")
                // ->whereRaw("Date(sales_bills.created_at) between '$firstDay' and '$lastDay'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $monthReturn = return_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$firstDay' and '$lastDay'")
                // ->whereRaw("Date(return_bills.created_at) between '$firstDay' and '$lastDay'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $finalMonthSales = $monthSales - $monthReturn;

            //////////////////////////// YEAR SALES ///////////////////////////////

            if (date('m') > 4) {
                $year = date('Y') + 1;
                $fdate = date('Y')."-01-04";
                $tdate = $year. "-03-".date('t');
            } else {
                $year = date('Y') - 1;
                $fdate = $year."-01-04";
                $tdate = date('Y') . "-03-" . date('t');
            }
            
            $yearSales = sales_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$fdate' and '$tdate'")
                // ->whereRaw("Date(sales_bills.created_at) between '$fdate' and '$tdate'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $yearReturn = return_bill::select('total_bill_amount')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(return_bills.bill_date,'%d-%m-%Y') between '$fdate' and '$tdate'")
                // ->whereRaw("Date(return_bills.created_at) between '$fdate' and '$tdate'")
                ->where('deleted_at','=',NULL)
                ->sum('total_bill_amount');

            $finalYearSales = $yearSales - $yearReturn;

            ////////////////////////////// TOTAL SALES COUNT /////////////////////////////

            $salesCount = sales_bill::select('sales_bill_id')->where('company_id', $company_id)
                ->whereRaw("STR_TO_DATE(sales_bills.bill_date,'%d-%m-%Y') between '$fdate' and '$tdate'")
                // ->whereRaw("Date(sales_bills.created_at) between '$fdate' and '$tdate'")
                ->where('deleted_at','=',NULL)
                ->count('sales_bill_id');

            /////////////////////////////// LOW OUT OF STOCK PRODUCTS ////////////////////////////////

            //, price_masters.product_qty, count(products.product_id) as totalCount

            // $lowStock = DB::select(DB::raw("(SELECT p.* FROM `products` as p WHERE p.alert_product_qty > (SELECT SUM(price_masters.product_qty) FROM price_masters WHERE price_masters.product_id = p.product_id))"));

            $lowStock = product::select('*')
            ->where('products.alert_product_qty', '>' ,DB::raw("(SELECT SUM(price_masters.product_qty) FROM price_masters WHERE price_masters.product_id = products.product_id)"))
             ->withCount([
                    'price_master as totalstock' => function($fquery)  {
                        $fquery->select(DB::raw('SUM(product_qty)'));
                    }
                ])->get();

            // echo '<pre>';
            // print_r($lowStock); exit;

            /////////////////////////////// CUSTOMER OUTSTANDING PAYMENTS ////////////////////////////////

            $customerbaldata = customer_creditaccount::select("*", DB::raw("SUM(credit_amount) as totalcreditamount"), DB::raw("SUM(balance_amount) as totalbalance"), DB::raw("(SELECT SUM(customer_creditreceipt_details.payment_amount) FROM customer_creditreceipt_details WHERE customer_creditreceipt_details.customer_id = customer_creditaccounts.customer_id and deleted_at IS NULL GROUP BY customer_creditreceipt_details.customer_id) as recdamt"))->groupBy('customer_id')->orderBy('customer_creditaccount_id', 'DESC')->where('deleted_at', '=', NULL)->whereRaw('balance_amount!=0')->with('customer')->take(5)->get();


            //ADDED BY HEMAXI..FOR SHOW NEAR EXPIRY DATE PRODUCT ACCORDING TO ALERT BEFORE PRODUCT EXPIRY DATE(FROM PRODUCT MODULE)
            $expiry_near_product =  product::where('company_id','=',$company_id)
                      ->where('days_before_product_expiry','!=',0)
                      ->with('inward_product_detail')
                      ->whereHas('inward_product_detail',function($q) use($today)
                      {
                          $q->where('inward_product_details.expiry_date','!=','');
                      })->get();


            //END OF CODE OF HEMAXI FOR GETTING PRODUCT WHICH IS NEAREST TO ALERT DAYS


            return view('dashboard', compact('finalTodaySales', 'finalMonthSales', 'finalYearSales', 'salesCount', 'lowStock', 'customerbaldata','expiry_near_product'));
        }
    }

    public function showChangePasswordForm()
    {
        return view('auth.changePassword');
    }
    
    public function login()
    {
        return view('auth.login');
    }
    
    public function my_profile()
    {
        $userId             =   Auth::User()->user_id;

        $state = state::all();
        $country = country::all();

        $result     =   user::where('user_id',$userId)->whereNull('deleted_at')->get();
        return view('auth.my_profile',compact('result','state','country'));
    }


    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        auth()->logout();
        return redirect('/')->with("success","Password changed successfully !");
    }

    public function universal_search(Request $request)
    {
        $universalKeyword        =   $request->search_val;

        $result     =   home_navigations_data::select('home_navigation_id','nav_tab_display_name','nav_url')->where('nav_keywords','LIKE','%'.$universalKeyword.'%')
        ->with('home_navigation')->get();

        return json_encode(array("Success"=>"True","Data"=>$result));

    }
}
