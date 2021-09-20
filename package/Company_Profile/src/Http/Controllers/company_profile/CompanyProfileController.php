<?php

namespace Retailcore\Company_Profile\Http\Controllers\company_profile;
use App\home_navigations_data;
use App\Http\Controllers\Controller;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Illuminate\Http\Request;
use App\state;
use App\country;
use Illuminate\Validation\Rule;
use Auth;
use DB;
class CompanyProfileController extends Controller
{

    public function index()
    {
            $state = state::all();
            $country = country::all();
            $company_profile = company_profile::where('company_id', Auth::user()->company_id)->first();

            return view('company_profile::company_profile/company_profile', compact('company_profile', 'state', 'country'));

    }


    public function company_profile_create(Request $request)
    {
        $data = $request->all();
        $company_profile_data =  array();

        parse_str($data['formdata'], $company_profile_data);

        $company_profile_data = preg_replace('/\s+/', ' ', $company_profile_data);

        $validate_error = \Validator::make($company_profile_data,
            [
                'full_name' => ['required'],
                'personal_mobile_no' => ['required', Rule::unique('company_profiles')->ignore($company_profile_data['company_profile_id'], 'company_profile_id')],
                'personal_email' => ['required',Rule::unique('company_profiles')->ignore($company_profile_data['company_profile_id'], 'company_profile_id')],
                'company_name' => ['required',Rule::unique('company_profiles')->ignore($company_profile_data['company_profile_id'], 'company_profile_id')],
                'company_address' => ['required'],
                'company_area' => ['required'],
                'company_city' => ['required'],
                'company_pincode' => ['required'],
            ]);
        if($validate_error-> fails())
        {
            return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_error->messages()));
            exit;
        }

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;
        $created_by = $userId;


        try {
            DB::beginTransaction();

            $update_cmp_profile = company_profile::updateOrCreate(
                ['company_profile_id' => $company_profile_data['company_profile_id'],
                    'company_id' => $company_id,],
                [
                    // 'company_id'=>$company_id,
                    'full_name' => (isset($company_profile_data['full_name']) ? $company_profile_data['full_name'] : ''),
                    'personal_mobile_dial_code' => (isset($company_profile_data['personal_mobile_dial_code']) ? $company_profile_data['personal_mobile_dial_code'] : ''),
                    'personal_mobile_no' => (isset($company_profile_data['personal_mobile_no']) ? $company_profile_data['personal_mobile_no'] : ''),
                    'personal_email' => (isset($company_profile_data['personal_email']) ? $company_profile_data['personal_email'] : ''),
                    'company_name' => (isset($company_profile_data['company_name']) ? $company_profile_data['company_name'] : ''),
                    'company_mobile_dial_code' => (isset($company_profile_data['company_mobile_dial_code']) ? $company_profile_data['company_mobile_dial_code'] : ''),
                    'company_mobile' => (isset($company_profile_data['company_mobile']) ? $company_profile_data['company_mobile'] : ''),
                    'company_email' => (isset($company_profile_data['company_email']) ? $company_profile_data['company_email'] : ''),
                    'website' => (isset($company_profile_data['website']) ? $company_profile_data['website'] : ''),
                    'gstin' => (isset($company_profile_data['gstin']) ? $company_profile_data['gstin'] : ''),
                    'state_id' => (isset($company_profile_data['state_id']) && $company_profile_data['state_id'] != '' ? $company_profile_data['state_id'] : NULL),
                    'whatsapp_mobile_dial_code' => (isset($company_profile_data['whatsapp_mobile_dial_code']) ? $company_profile_data['whatsapp_mobile_dial_code'] : ''),
                    'whatsapp_mobile_number' => (isset($company_profile_data['whatsapp_mobile_number']) ? $company_profile_data['whatsapp_mobile_number'] : ''),
                    'facebook' => (isset($company_profile_data['facebook']) ? $company_profile_data['facebook'] : ''),
                    'instagram' => (isset($company_profile_data['instagram']) ? $company_profile_data['instagram'] : ''),
                    'pinterest' => (isset($company_profile_data['pinterest']) ? $company_profile_data['pinterest'] : ''),
                    'company_address' => (isset($company_profile_data['company_address']) ? $company_profile_data['company_address'] : ''),
                    'company_area' => (isset($company_profile_data['company_area']) ? $company_profile_data['company_area'] : ''),
                    'company_city' => (isset($company_profile_data['company_city']) ? $company_profile_data['company_city'] : ''),
                    'company_pincode' => (isset($company_profile_data['company_pincode']) ? $company_profile_data['company_pincode'] : ''),
                    'country_id' => (isset($company_profile_data['country_id']) && $company_profile_data['country_id'] != '' ? $company_profile_data['country_id'] : NULL),
                    'authorized_signatory_for' => (isset($company_profile_data['authorized_signatory_for']) ? $company_profile_data['authorized_signatory_for'] : ''),
                    'terms_and_condition' => (isset($company_profile_data['terms_and_condition']) ? $company_profile_data['terms_and_condition'] : ''),
                    'additional_message' => (isset($company_profile_data['additional_message']) ? $company_profile_data['additional_message'] : ''),
                    'return_days' => (isset($company_profile_data['returndays']) ? $company_profile_data['returndays'] : ''),
                    'bill_number_prefix' => (isset($company_profile_data['bill_number_prefix']) ? $company_profile_data['bill_number_prefix'] : ''),
                    'credit_receipt_prefix' => (isset($company_profile_data['credit_receipt_prefix']) ? $company_profile_data['credit_receipt_prefix'] : ''),
                    'debit_receipt_prefix' => (isset($company_profile_data['debit_receipt_prefix']) ? $company_profile_data['debit_receipt_prefix'] : ''),
                    'po_number_prefix' => (isset($company_profile_data['po_number_prefix']) ? $company_profile_data['po_number_prefix'] : ''),
                    'account_holder_name' => (isset($company_profile_data['account_holder_name']) ? $company_profile_data['account_holder_name'] : ''),
                    'bank_name' => (isset($company_profile_data['bank_name']) ? $company_profile_data['bank_name'] : ''),
                    'account_number' => (isset($company_profile_data['account_number']) ? $company_profile_data['account_number'] : NULL),
                    'ifsc_code' => (isset($company_profile_data['ifsc_code']) ? $company_profile_data['ifsc_code'] : ''),
                    'branch' => (isset($company_profile_data['branch']) ? $company_profile_data['branch'] : ''),
                    'po_terms_and_condition' => (isset($company_profile_data['po_terms_and_condition']) ? $company_profile_data['po_terms_and_condition'] : '')
                ]);
            DB::commit();
        }catch(\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }


            $company_profile_id = $update_cmp_profile->company_profile_id;
            if ($update_cmp_profile) {
                if ($company_profile_data['company_profile_id'] != '') {

                    return json_encode(array("Success" => "True", "Message" => "Company Profile has been successfully updated.", "url" => "", "company_profile_id" => $company_profile_id));
                } else {
                    return json_encode(array("Success" => "True", "Message" => "Company Profile has been successfully added.", "url" => "home", "company_profile_id" => $company_profile_id));

                }
            } else {
                return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));
            }
        }




    public function software_configuration_create(Request $request)
    {
        $data = $request->all();
        $software_configuration_data =  array();

        parse_str($data['formdata'], $software_configuration_data);

        $software_configuration_data = preg_replace('/\s+/', ' ', $software_configuration_data);

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;

        try {
            DB::beginTransaction();
            $software_configuration = company_profile::updateOrCreate(
                ['company_profile_id' => $software_configuration_data['company_profile_id'],
                    'company_id' => $company_id,],
                [
                    'created_by' => $userId,
                    'company_id' => $company_id,
                    'country_id' => 102,
                    'tax_type' => (isset($software_configuration_data['tax_type']) ? $software_configuration_data['tax_type'] : ''),
                    'tax_title' => (isset($software_configuration_data['tax_title']) ? $software_configuration_data['tax_title'] : ''),
                    'currency_title' => (isset($software_configuration_data['currency_title']) ? $software_configuration_data['currency_title'] : ''),
                    'decimal_points' => (isset($software_configuration_data['decimal_points']) ? $software_configuration_data['decimal_points'] : 0),
                    'billtype' => (isset($software_configuration_data['billtype']) ? $software_configuration_data['billtype'] : ''),
                    'series_type' => (isset($software_configuration_data['series_type']) ? $software_configuration_data['series_type'] : ''),
                    'billprint_type' => (isset($software_configuration_data['billprint_type']) ? $software_configuration_data['billprint_type'] : ''),
                    'navigation_type' => (isset($software_configuration_data['navigation_type']) ? $software_configuration_data['navigation_type'] : ''),
                    'inward_type' => (isset($software_configuration_data['inward_type']) ? $software_configuration_data['inward_type'] : ''),
                    'inward_calculation' => (isset($software_configuration_data['inward_calculation']) ? $software_configuration_data['inward_calculation'] : 1),
                ]
            );
            DB::commit();
            $company_profile_id = $software_configuration->company_profile_id;
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }


        if(isset($software_configuration_data['inward_type']) && $software_configuration_data['inward_type'] !== '')
        {
            $hide_module = array();
            $hide_module_name = '';
            if($software_configuration_data['inward_type'] ==1)
            {
                //fmcg
                $hide[0]['module_name'] = 'inward_stock_show';
                $hide[0]['active'] = 0;

                $hide[1]['module_name'] = 'inward_stock';
                $hide[1]['active'] = 1;

                $hide[2]['module_name'] = 'batch_no_wise_report';
                $hide[2]['active'] = 1;

            }
            if($software_configuration_data['inward_type'] ==2)
            {
                //garment
                $hide[0]['module_name'] = 'inward_stock';
                $hide[0]['active'] = 0;

                $hide[1]['module_name'] = 'inward_stock_show';
                $hide[1]['active'] = 1;

                $hide[2]['module_name'] = 'batch_no_wise_report';
                $hide[2]['active'] = 0;
            }

            if($hide != '')
            {
                foreach ($hide as $item) {
                    home_navigations_data::where('company_id',$company_id)
                    ->where('nav_url',$item['module_name'])
                    ->update(array(
                       'is_active' => $item['active'],
                        'modified_by' =>$userId
                    ));
                }
            }
        }

        if($software_configuration)
        {
            if ($software_configuration_data['company_profile_id'] != '')
            {

                return json_encode(array("Success"=>"True","Message"=>"Software Configuration has been successfully updated.","url"=>"","company_profile_id"=>$company_profile_id));
            }
            else
            {
                return json_encode(array("Success"=>"True","Message"=>"Software Configuration has been successfully added.","url"=>"company_profile","company_profile_id"=>$company_profile_id));

            }
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
    }

    public function valid_technical_team(Request $request)
    {
        $data = $request->all();
        $validate_team =  array();

        parse_str($data['formdata'], $validate_team);

        $validate_team = preg_replace('/\s+/', ' ', $validate_team);

        if(md5($validate_team['configuration_password']) == ('fc6fa2d0aaab4a80fba1832f23960331'))
        {
            return json_encode(array("Success"=>"True"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Successfull!"));
        }

    }


}
