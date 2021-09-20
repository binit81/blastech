<?php

namespace Retailcore\Customer\Http\Controllers\customer;
use App\Http\Controllers\product\ProductImageController;
use mysql_xdevapi\Exception;
use Retailcore\Customer\Models\customer\customer_template;
use App\Http\Controllers\Controller;

use function foo\func;
use Retailcore\Customer\Models\customer\customer;
use Retailcore\Customer\Models\customer\customer_address_detail;
use Retailcore\Customer_Source\Models\customer_source\customer_source;
use Illuminate\Http\Request;
use App\state;
use App\country;
use App\company;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

use Auth;

use DB;
use Retailcore\Sales\Models\sales_bill;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = customer::where('company_id',Auth::user()->company_id)
                    ->where('deleted_at','=',NULL)
                    ->with('customer_address_detail')
                    ->with('customer_source')
                    ->orderBy('customer_id', 'DESC')
                    ->paginate(10);


        foreach($customer AS $key=>$value)
        {
            $customer_count = sales_bill::where('company_id',Auth::user()->company_id)
                ->whereNull('deleted_at')
                ->where('customer_id',$value['customer_id'])
                ->count();
            $customer[$key]['delete_option'] = 1;
            if($customer_count > 0)
            {
                $customer[$key]['delete_option'] = 0;
            }

        }
        $customer_source = customer_source::where('company_id',Auth::user()->company_id)
                        ->where('deleted_at','=',NULL)
                        ->orderBy('customer_source_id','DESC')->get();

        $state = state::all();
        $country = country::all();
        $total = $customer->total();
        return view('customer::customer_show',compact('customer','state','country','customer_source'));
    }

    public function customer_data(Request $request)
    {
        if($request->ajax())
        {
            $customer = customer::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->with('customer_address_detail')
                ->orderBy('customer_id', 'DESC')
                ->paginate(10);

            foreach($customer AS $key=>$value)
            {
                $customer_count = sales_bill::where('company_id',Auth::user()->company_id)
                    ->whereNull('deleted_at')
                    ->where('customer_id',$value['customer_id'])
                    ->count();
                $customer[$key]['delete_option'] = 1;
                if($customer_count > 0)
                {
                    $customer[$key]['delete_option'] = 0;
                }

            }

            $state = state::all();
            $country = country::all();
            $total = $customer->total();

            return view('customer::customer_data', compact('customer','state','country','total'))->render();
        }
    }
    function customer_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = $data['query'];

            $query = str_replace(" ", "%", $query);

            $customer = customer::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->with('customer_address_detail');

            if(isset($query) && $query != '' && $query['customer_name'] != '')
            {
                $customer->where('customer_name', 'like', '%'.$query['customer_name'].'%');
            }

            if(isset($query) && $query != '' && $query['customer_mobile'] != '')
            {
                $customer->where('customer_mobile', 'like', '%'.$query['customer_mobile'].'%');
            }

            if(isset($query) && $query != '' && $query['customer_email'] != '')
            {
                $customer->where('customer_email', 'like', '%'.$query['customer_email'].'%');
            }

            if(isset($query) && $query != '' && $query['customer_gstin'] != '')
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('customer_gstin', 'like', '%'.$query['customer_gstin'].'%');
                });
            }

            if(isset($query) && $query != '' && $query['customer_date_of_birth'] != '')
            {
                $customer->where('customer_date_of_birth', 'like', '%'.$query['customer_date_of_birth'].'%');
            }
            if(isset($query) && $query != '' && $query['customer_area'] != '')
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('customer_area', 'like', '%'.$query['customer_area'].'%');
                });
            }
            if(isset($query) && $query != '' && $query['customer_city'] != '')
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('customer_city', 'like', '%'.$query['customer_city'].'%');
                });
            }
            if(isset($query) && $query != '' && $query['customer_pincode'] != '')
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('customer_pincode', 'like', '%'.$query['customer_pincode'].'%');
                });
            }
            if(isset($query) && $query != '' && $query['state_id'] != ''  && $query['state_id'] != 0)
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('state_id', 'like', '%'.$query['state_id'].'%');
                });
            }
            if(isset($query) && $query != '' && $query['country_id'] != '' && $query['country_id'] != 0)
            {
                $customer->whereHas('customer_address_detail',function ($q) use($query){
                    $q->where('country_id', 'like', '%'.$query['country_id'].'%');
                });
            }

            $customer = $customer->orderBy($sort_by,$sort_type)->paginate(10);

            foreach($customer AS $key=>$value)
            {
                $customer_count = sales_bill::where('company_id',Auth::user()->company_id)
                    ->whereNull('deleted_at')
                    ->where('customer_id',$value['customer_id'])
                    ->count();
                $customer[$key]['delete_option'] = 1;
                if($customer_count > 0)
                {
                    $customer[$key]['delete_option'] = 0;
                }

            }

            $state = state::all();
            $country = country::all();

            return view('customer::customer_data', compact('customer','state','country'))->render();
        }
    }

    public function customer_create(Request $request)
    {
        $data = $request->all();

        $customerdata =  array();

        $state_id = company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();

        parse_str($data['formdata'], $customerdata);

        $customerdata = preg_replace('/\s+/', ' ', $customerdata);

        $validate_error = \Validator::make($customerdata,
            [
                'customer_mobile' => [Rule::unique('customers')->ignore($customerdata['customer_id'], 'customer_id')->whereNull('deleted_at')->whereNotNull('customer_mobile')],
                'customer_email' => [Rule::unique('customers')->ignore($customerdata['customer_id'], 'customer_id')->whereNull('deleted_at')->whereNotNull('customer_email')],
                'customer_gstin' => [Rule::unique('customer_address_details')->ignore($customerdata['customer_id'], 'customer_id')->whereNull('deleted_at')->whereNotNull('customer_gstin')],
            ]);

        if($validate_error-> fails())
        {
            return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_error->messages()));
            exit;
        }

        $userId = Auth::User()->user_id;

        $company_id = Auth::User()->company_id;

        $created_by = $userId;

        try{
            DB::beginTransaction();
        $customer = customer::updateOrCreate(
            ['customer_id' => $customerdata['customer_id'], 'company_id'=>$company_id,],
            [
                'created_by' =>$created_by,
                'company_id'=>$company_id,
                'customer_name' => (isset($customerdata['customer_name'])?$customerdata['customer_name'] : ''),
                'customer_gender' => (isset($customerdata['gender'])?$customerdata['gender'] : NULL),
                'customer_mobile_dial_code' => (isset($customerdata['customer_mobile_dial_code'])?$customerdata['customer_mobile_dial_code'] : ''),
                'customer_mobile' => (isset($customerdata['customer_mobile']) && $customerdata['customer_mobile'] != '' ?$customerdata['customer_mobile'] : NULL),
                'customer_email' => (isset($customerdata['customer_email']) && $customerdata['customer_email'] != '' ?$customerdata['customer_email'] : NULL),
                'customer_date_of_birth' => (isset($customerdata['customer_date_of_birth'])?$customerdata['customer_date_of_birth'] : ''),
                'outstanding_duedays' => (isset($customerdata['outstanding_duedays'])?$customerdata['outstanding_duedays'] : NULL),
                'customer_source_id' => (isset($customerdata['source']) && $customerdata['source'] != '' ?$customerdata['source'] : null),
                'note' => (isset($customerdata['customer_note'])?$customerdata['customer_note'] : NULL),
                'is_active' => "1"
            ]
        );
        $customer_id = $customer->customer_id;

        if($customerdata['customer_gstin'] != '' || $customerdata['customer_address'] != '' || $customerdata['customer_area'] || $customerdata['customer_city'] != '' || $customerdata['customer_pincode'] != '' || $customerdata['state_id'] != '' || $customerdata['state_id'] == '' ||$customerdata['country_id'] != '' )
        {
            $customer_address = customer_address_detail::updateOrCreate(
                ['customer_id' => $customer_id,
                 'company_id'=>$company_id,],
                [
                    'created_by' =>$created_by,
                    'company_id'=>$company_id,
                    'customer_id'=>$customer_id,
                    'customer_gstin' => (isset($customerdata['customer_gstin'])?$customerdata['customer_gstin'] : ''),
                    'customer_address_type' => '1',
                    'customer_address' => (isset($customerdata['customer_address'])?$customerdata['customer_address'] : ''),
                    'customer_area' => (isset($customerdata['customer_area'])?$customerdata['customer_area'] : ''),
                    'customer_city' => (isset($customerdata['customer_city'])?$customerdata['customer_city'] : ''),
                    'customer_pincode' => (isset($customerdata['customer_pincode'])?$customerdata['customer_pincode'] : ''),
                    'state_id' => (isset($customerdata['state_id']) && $customerdata['state_id'] != 0?$customerdata['state_id'] : $state_id[0]['state_id']),
                    'country_id' => (isset($customerdata['country_id']) && $customerdata['country_id'] != ''?$customerdata['country_id'] : NULL),
                    'is_active' => "1"
                ]
            );
        }
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }


        if($customer)
        {
            if ($customerdata['customer_id'] != '')
            {

                return json_encode(array("Success"=>"True","Message"=>"Customer has been successfully updated.","customer_id"=>$customer_id));
            }
            else
            {
                return json_encode(array("Success"=>"True","Message"=>"Customer has been successfully added.","customer_id"=>$customer_id));
            }
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }


    }


    public function customer_edit(Request $request)
    {
        $customer_id = decrypt($request->customer_id);

        $customerdata = customer::where([
            ['customer_id','=',$customer_id],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('customer_address_detail')
            ->first();

        return json_encode(array("Success"=>"True","Data"=>$customerdata));
    }

    public function customer_delete(Request $request)
    {
        $userId = Auth::User()->user_id;
        $customer_delete =  customer::whereIn('customer_id', $request->deleted_id)
            ->update([
                'deleted_by' => $userId,
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

        $customeraddress_delete =  customer_address_detail::whereIn('customer_id', $request->deleted_id)
            ->update([
                'deleted_by' => $userId,
                'deleted_at' => date('Y-m-d H:i:s')
            ]);

        if($customer_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Customer has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
        }
    }


    public function customer_template(Request $request)
    {
        return Excel::download(new customer_template,'customer.xlsx');
    }

    public function customer_check(Request $request)
    {
        $customer_excel_data = $request->all();

        if(isset($customer_excel_data) && $customer_excel_data != '' && !empty($customer_excel_data))
        {
            $error = 0;
            foreach ($customer_excel_data AS $key=>$value) {

                $validate_value['customer_mobile'] = $value['Mobile No.'];
                $validate_value['customer_email'] = $value['Email'];
                $validate_value['customer_gstin'] = $value['GSTIN'];

                if ($value['Mobile No.'] != '')
                {
                    if (customer::where('customer_mobile', $value['Mobile No.'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "Mobile No. " . $value['Mobile No.'] . " Already exist!"));
                        exit;
                    }
                }
                if ($value['Email'] != '')
                {
                    if (customer::where('customer_email', $value['Email'])->exists()) {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "Email ID. " . $value['Email'] . " Already exist!"));
                        exit;
                    }
                }
                if ($value['GSTIN'] != '')
                {
                    if (customer_address_detail::where('customer_gstin', $value['GSTIN'])->exists()) {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "GST IN. " . $value['GSTIN'] . " Already exist!"));
                        exit;
                    }
                }

                if ($value['State / Region'] != '')
                {
                    if (!state::where('state_name', $value['State / Region'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "State " . $value['State / Region'] . " Not Found!"));
                        exit;
                    }
                }
                if ($value['Country'] != '')
                {
                    if (!country::where('country_name', $value['Country'])->exists())
                    {
                        $error = 1;
                        return json_encode(array("Success" => "False", "Message" => "Country " . $value['Country'] . " Not Found!"));
                        exit;
                    }
                }
            }

          if($error == 0)
          {
              $userId = Auth::User()->user_id;
              $company_id = Auth::User()->company_id;
              $created_by = $userId;
              try{
                  DB::beginTransaction();
                  $state_id = company_profile::select('state_id')->where('company_id',Auth::user()->company_id)->get();

                  foreach ($customer_excel_data AS $key=>$value)
                  {
                      $dial_code = '';
                      if($value['Mobile No.'] != '')
                      {
                          if($value['Customer Mobile Country Code'] == '')
                          {
                              $dial_code = company_profile::select('company_mobile_dial_code')
                                  ->where('company_id',Auth::user()->company_id)->first();

                              $code = explode(',',$dial_code['company_mobile_dial_code']);

                              $dial_code = $code[0];
                          }
                          else
                          {
                              $dial_code = $value['Customer Mobile Country Code'];
                          }
                      }

                      if($value['Customer Mobile Country Code'] != '' && $dial_code == '')
                      {
                          $dial_code = '+'.$value['Customer Mobile Country Code'];
                      }
                      else
                      {
                          $dial_code = company_profile::select('company_mobile_dial_code')->where('company_id',Auth::user()->company_id)->first();
                          $code = explode(',',$dial_code['company_mobile_dial_code']);
                          $dial_code =$code[0];
                      }

                      $customer_source_id = null;


                      if($value['How did you came to know about us?'] != '')
                      {
                         $customer_source =  customer_source::where('source_name','=',$value['How did you came to know about us?'])
                              ->where('company_id',Auth::user()->company_id)
                              ->where('deleted_at','=',NULL)
                              ->select('customer_source_id')
                              ->first();

                         if(isset($customer_source) && isset($customer_source['customer_source_id']) && $customer_source['customer_source_id'] != '')
                         {

                             $customer_source_id = $customer_source->customer_source_id;
                         }
                         else
                         {

                             $source = customer_source::updateOrCreate(
                                 ['customer_source_id' => '',
                                     'company_id'=>$company_id,],
                                 [
                                     'created_by' =>$created_by,
                                     'company_id'=>$company_id,
                                     'source_name' => (isset($value['How did you came to know about us?'])?$value['How did you came to know about us?'] : ''),
                                     'note' => NULL,
                                     'is_active' => "1"
                                 ]
                             );

                             $customer_source_id = $source->customer_source_id;

                         }
                      }
                     $dob = '';


                      if(($value['Day of Birth(DD)'] != '' && $value['Day of Birth(DD)'] != null) || ($value['Month of Birth(MM)'] != '' && $value['Month of Birth(MM)'] != null) || ($value['Year of Birth(YYYY)'] != '' && $value['Year of Birth(YYYY)'] != null))
                      {
                          $dob = $value['Day of Birth(DD)'].'-'.$value['Month of Birth(MM)'].'-'.$value['Year of Birth(YYYY)'];
                      }

                      $gender = 0;

                      if(isset($value['Gender']) && $value['Gender'] != '')
                      {
                          if($value['Gender'] == "male" || $value['Gender'] == "Male" || $value['Gender'] == "MALE")
                          {
                              $gender = 1;
                          }

                          if($value['Gender'] == "female" || $value['Gender'] == "Female" || $value['Gender'] == "FEMALE")
                          {
                              $gender = 2;
                          }

                          if($value['Gender'] == "transgender" || $value['Gender'] == "Transgender" || $value['Gender'] == "TRANSGENDER")
                          {
                              $gender = 3;
                          }

                      }

                      $customer = customer::updateOrCreate(
                          ['customer_id' => '', 'company_id' => $company_id,],
                          [
                              'created_by' => $created_by,
                              'company_id' => $company_id,
                              'customer_name' => (isset($value['Customer Name']) ? $value['Customer Name'] : ''),
                              'customer_gender' => (isset($gender)?$gender : 0),
                              'customer_mobile_dial_code' => (isset($dial_code) ? $dial_code : ''),
                              'customer_mobile' => (isset($value['Mobile No.']) && $value['Mobile No.'] != '' ? $value['Mobile No.'] : NULL),
                              'customer_email' => (isset($value['Email']) && $value['Email'] != '' ? $value['Email'] : NULL),
                              'customer_date_of_birth' => (isset($dob) && $dob != '' ? $dob : ''),
                              'outstanding_duedays' => (isset($value['Credit Period(days)']) ? $value['Credit Period(days)'] : NULL),
                              'customer_source_id' => (isset($customer_source_id) ? $customer_source_id : NULL),
                              'note' => (isset($value['Note']) ? $value['Note'] : NULL),
                              'is_active' => "1"
                          ]
                      );

                      $customer_id = $customer->customer_id;

                      /*if ($value['GSTIN'] != '' || $value['Flat no.,Building,Street etc.'] != '' || $value['Area'] || $value['City / Town'] != '' || $value['Pin / Zip Code'] != '' || $value['State / Region'] != '' || $value['Country'] != '')
                      {*/

                          if ($value['State / Region'] != '')
                          {
                           $state_excel = state::select('state_id')->where('state_name', $value['State / Region'])->first();
                            $state_id_excel = $state_excel['state_id'];
                          }

                          if ($value['Country'] != '')
                          {
                              $country_excel = country::select('country_id')->where('country_name', $value['Country'])->first();
                              $country_id_excel = $country_excel['country_id'];
                          }

                          $customer_address = customer_address_detail::updateOrCreate(
                              ['customer_id' => $customer_id,
                                  'company_id' => $company_id,],
                              [
                                  'created_by' => $created_by,
                                  'company_id' => $company_id,
                                  'customer_id' => $customer_id,
                                  'customer_gstin' => (isset($value['GSTIN']) ? $value['GSTIN'] : ''),
                                  'customer_address_type' => '1',
                                  'customer_address' => (isset($value['Flat no.,Building,Street etc.']) ? $value['Flat no.,Building,Street etc.'] : ''),
                                  'customer_area' => (isset($value['Area']) ? $value['Area'] : ''),
                                  'customer_city' => (isset($value['City / Town']) ? $value['City / Town'] : ''),
                                  'customer_pincode' => (isset($value['Pin / Zip Code']) ? $value['Pin / Zip Code'] : ''),
                                  'state_id' => (isset($state_id_excel) && $state_id_excel != 0 ? $state_id_excel : $state_id[0]['state_id']),
                                  'country_id' => (isset($country_id_excel) && $country_id_excel != '' ? $country_id_excel : 102),
                                  'is_active' => "1"
                              ]
                          );
                      /*}*/
                      DB::commit();
                      if ($customer)
                      {
                          if(!next( $customer_excel_data ))
                          {

                              return json_encode(array("Success" => "True", "Message" => "Customer has been successfully Added."));
                          }
                      }
                      else
                      {
                          return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));
                          exit;
                      }
                  }


              }catch (\Illuminate\Database\QueryException $e)
              {

                  DB::rollback();
                  return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
              }
          }
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"No Row!"));
        }
    }



    public function customer_dependency(Request $request)
    {
        try {


            $customer_id = decrypt($request->id);

            $customer_dependent = sales_bill::where('company_id',Auth::User()->company_id)
                                ->whereNull('deleted_at')
                                ->where('customer_id',$customer_id)
                                ->get();

            $dependent_array = [];

            foreach ($customer_dependent AS $dependent_key=>$dependent_value)
            {
                $detail = array('Bill No' => $dependent_value->bill_no,
                        'Bill Date' => $dependent_value->bill_date);

                $dependent_array[] = array(
                        'Module_Name'=> "Sales Bill",
                        'detail' => $detail
                    );
            }

            return json_encode(array("Success"=>"True","Data"=>$dependent_array));

        }
        catch(Exception $e)
        {
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }
    }


}
