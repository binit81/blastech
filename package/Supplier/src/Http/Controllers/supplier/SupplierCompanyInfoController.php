<?php

namespace Retailcore\Supplier\Http\Controllers\supplier;
use App\Http\Controllers\Controller;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Supplier\Models\supplier\supplier_bank;
use Retailcore\Supplier\Models\supplier\supplier_company_info;
use Retailcore\Supplier\Models\supplier\supplier_gst;
use Retailcore\Supplier\Models\supplier\supplier_treatment;
use Retailcore\Supplier\Models\supplier\supplier_contact_details;
use Retailcore\Supplier\Models\supplier\salutation;
use App\state;
use App\country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use DB;

class SupplierCompanyInfoController extends Controller
{

    public function index()
    {
        $supplier= supplier_company_info::where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->orderBy('supplier_company_info_id', 'DESC')
            ->paginate(10);

        foreach($supplier AS $key=>$value)
        {
            $supplier_count = inward_stock::where('company_id',Auth::user()->company_id)
                            ->whereNull('deleted_at')
                            ->where('supplier_gst_id',$value['supplier_gst'][0]['supplier_gst_id'])
                            ->count();

            $supplier[$key]['delete_option'] = 1;
             if($supplier_count > 0)
             {
                    $supplier[$key]['delete_option'] = 0;
             }

        }

        $supplier_treatments = supplier_treatment::where('is_active','=',1)->whereNull('deleted_at')->get();
        $salutation = salutation::get();
        $state = state::get();
        $country = country::get();

        return view('supplier::supplier/supplier_show',compact('supplier','supplier_treatments','salutation','state','country'));
    }

    public function add_supplier(Request $request)
    {
        $data = $request->all();
        $company_id = Auth::User()->company_id;
        $validate_error = \Validator::make($data['supplier_company_info'],
            [
                'supplier_company_name' => ['required', Rule::unique('supplier_company_infos')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->whereNull('deleted_at')->whereNotNull('supplier_company_name')],
               // 'supplier_first_name' => ['required', Rule::unique('supplier_company_infos')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->whereNull('deleted_at')->whereNotNull('supplier_first_name')],
               // 'supplier_pan_no' => ['required', Rule::unique('supplier_company_infos')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->whereNull('deleted_at')->whereNotNull('supplier_pan_no')],
            ]);


        if($validate_error-> fails())
        {
            return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_error->messages()));
            exit;
        }

        foreach($data['supplier_bank_info'] AS $key=>$value)
        {
            $validate_bank_error = \Validator::make($value,
                [
                    //'supplier_bank_name' => ['required', Rule::unique('supplier_banks')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_bank_id'],'supplier_bank_id')->whereNull('deleted_at')->whereNotNull('supplier_bank_name')],
                   // 'supplier_bank_account_name' => ['required', Rule::unique('supplier_banks')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_bank_id'],'supplier_bank_id')->whereNull('deleted_at')->whereNotNull('supplier_bank_account_name')],
                    'supplier_bank_account_no' => ['required', Rule::unique('supplier_banks')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_bank_id'],'supplier_bank_id')->whereNull('deleted_at')->whereNotNull('supplier_bank_account_no')],
                ]);
            if($validate_bank_error-> fails())
            {
                return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_bank_error->messages()));
                exit;
            }
        }
       foreach($data['supplier_gst_info'] AS $key=>$value)
        {
           $supplier_gst_id =  isset($value['supplier_gst_id']) ? $value['supplier_gst_id'] : '';

           $validate_gst_error = \Validator::make($value,
                [
                    'supplier_gstin' => ['required',
                        Rule::unique('supplier_gsts')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')
                            ->ignore($supplier_gst_id,'supplier_gst_id')
                            ->whereNull('deleted_at')
                            ->whereNotNull('supplier_gstin')],
                ]);

           if($validate_gst_error-> fails())
            {
                return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_gst_error->messages()));
                exit;
            }
        }


        foreach($data['supplier_contact_info'] AS $key=>$value)
        {
            $validate_gst_error = \Validator::make($value,
                [
                   // 'salutation_id' => ['required',Rule::unique('supplier_contact_details')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_contact_details_id'],'supplier_contact_details_id')->whereNull('deleted_at')->whereNotNull('salutation_id')],
                  //  'supplier_contact_firstname' => ['required', Rule::unique('supplier_contact_details')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_contact_details_id'],'supplier_contact_details_id')->whereNull('deleted_at')->whereNotNull('supplier_contact_firstname')],
                    'supplier_contact_email_id' => ['required',
                        Rule::unique('supplier_contact_details')
                            ->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_contact_details_id'],'supplier_contact_details_id')->whereNull('deleted_at')->whereNotNull('supplier_contact_email_id')],
                    'supplier_contact_mobile_no' => ['required', Rule::unique('supplier_contact_details')->ignore($data['supplier_company_info']['supplier_company_info_id'], 'supplier_company_info_id')->ignore($value['supplier_contact_details_id'],'supplier_contact_details_id')->whereNull('deleted_at')->whereNotNull('supplier_contact_mobile_no')],
                ]);
            if($validate_gst_error-> fails())
            {
                return json_encode(array("Success"=>"False","status_code"=>409,"Message"=>$validate_gst_error->messages()));
                exit;
            }
        }

        try {
            DB::beginTransaction();

            //update all value of supplier bank set deleted_by  and deleted
            supplier_bank::where('supplier_company_info_id', $data['supplier_company_info']['supplier_company_info_id'])->update(array(
                'deleted_by' => Auth::User()->user_id,
                'deleted_at' => date('Y-m-d H:i:s')
            ));
            //update all value of supplier gst set deleted_by  and deleted
            supplier_gst::where('supplier_company_info_id', $data['supplier_company_info']['supplier_company_info_id'])->update(array(
                'deleted_by' => Auth::User()->user_id,
                'deleted_at' => date('Y-m-d H:i:s')
            ));
            //update all value of supplier Customer set deleted_by  and deleted
            supplier_contact_details::where('supplier_company_info_id', $data['supplier_company_info']['supplier_company_info_id'])->update(array(
                'deleted_by' => Auth::User()->user_id,
                'deleted_at' => date('Y-m-d H:i:s')
            ));


            // supplier company info add
            $supplier_company_infos_insert = supplier_company_info::updateOrCreate(
                ['supplier_company_info_id' => $data['supplier_company_info']['supplier_company_info_id'], 'company_id' => $company_id,],
                $data['supplier_company_info']
            );
            $supplier_company_info_id = $supplier_company_infos_insert->supplier_company_info_id;

            //supplier bank add
            foreach ($data['supplier_bank_info'] AS $key => $value) {
                $value['deleted_at'] = NULL;
                $value['deleted_by'] = NULL;

                $supplier_bank_insert = supplier_bank::updateOrCreate(
                    ['supplier_company_info_id' => $supplier_company_info_id,
                        'company_id' => $company_id,
                        'supplier_bank_id' => $value['supplier_bank_id'],
                    ],
                    $value);
            }
            //end of supplier bank add


            //supplier gst add
            foreach ($data['supplier_gst_info'] AS $key => $value) {
                $supplier_gst_id = isset($value['supplier_gst_id']) ? $value['supplier_gst_id'] : '';
                $value['deleted_at'] = NULL;
                $value['deleted_by'] = NULL;
                $supplier_gst_insert = supplier_gst::updateOrCreate(
                    ['supplier_company_info_id' => $supplier_company_info_id,
                        'company_id' => $company_id,
                        'supplier_gst_id' => $supplier_gst_id,
                    ],
                    $value);


            }
            //end of supplier gst add
            //supplier Customer add
            foreach ($data['supplier_contact_info'] AS $key => $value) {
                $value['deleted_at'] = NULL;
                $value['deleted_by'] = NULL;
                $supplier_contact_insert = supplier_contact_details::updateOrCreate(
                    ['supplier_company_info_id' => $supplier_company_info_id,
                        'company_id' => $company_id,
                        'supplier_contact_details_id' => $value['supplier_contact_details_id'],
                    ],
                    $value);
            }
            //end of supplier Customer add

            DB::commit();
        }catch(\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }

        if($supplier_company_infos_insert)
        {
            if($data['supplier_company_info']['supplier_company_info_id'] != '')
            {
                return json_encode(array("Success"=>"True","Message"=>"Supplier successfully Update!"));
            }
            else
            {
                return json_encode(array("Success"=>"True","Message"=>"Supplier successfully Added!"));
            }
        }
    }


    public function customer_data(Request $request)
    {
        if($request->ajax())
        {
            $supplier = supplier_company_info::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->orderBy('supplier_company_info_id', 'DESC')
                ->paginate(10);

            foreach($supplier AS $key=>$value)
            {
                $supplier_count = inward_stock::where('company_id',Auth::user()->company_id)
                    ->whereNull('deleted_at')
                    ->where('supplier_gst_id',$value['supplier_gst'][0]['supplier_gst_id'])
                    ->count();

                $supplier[$key]['delete_option'] = 1;
                if($supplier_count > 0)
                {
                    $supplier[$key]['delete_option'] = 0;
                }
            }

            $supplier_treatments = supplier_treatment::where('is_active','=',1)->whereNull('deleted_at')->get();
            $salutation = salutation::get();
            $state = state::get();
            $country = country::get();

            return view('supplier::supplier/supplier_data', compact('supplier','supplier_treatments','salutation','state','country'))->render();
        }
    }



    public function supplier_edit(Request $request)
    {
        $supplier_company_info_id = decrypt($request->supplier_company_info_id);

        $supplier_data = supplier_company_info::where([
            ['supplier_company_info_id','=',$supplier_company_info_id],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->with('supplier_bank')
            ->with('supplier_gst')
            ->with('supplier_contact_detail')
            ->whereNull('deleted_at')
            ->first();

        return json_encode(array("Success"=>"True","Data"=>$supplier_data));
    }




    public function supplier_delete(Request $request)
    {
        $userId = Auth::User()->user_id;

        try {
            DB::beginTransaction();

            $supplier_company_delete = supplier_company_info::whereIn('supplier_company_info_id',
                $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);


            $supplier_banks_delete = supplier_bank::whereIn('supplier_company_info_id', $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $supplier_gst_delete = supplier_gst::whereIn('supplier_company_info_id', $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            $supplier_contact_delete = supplier_contact_details::whereIn('supplier_company_info_id', $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);

            DB::commit();
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }

        if($supplier_company_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Supplier has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
        }
    }


    public function supplier_dependency(Request $request)
    {
        $supplier_company_info_id = decrypt($request->id);
        $dependent_array = [];
        $supplier_gst = supplier_gst::where('company_id',Auth::User()->company_id)
                                      ->where('supplier_company_info_id',$supplier_company_info_id)
                                      ->select('supplier_gst_id')
                                      ->whereNull('deleted_at')
                                      ->get();

        foreach ($supplier_gst AS $gst_key=>$gst_value)
        {
            $inward_stock = inward_stock::where('company_id',Auth::User()->company_id)
                          ->whereIn('supplier_gst_id',[$gst_value['supplier_gst_id']])
                          ->whereNull('deleted_at')
                          ->select('invoice_date','inward_date','invoice_no')
                          ->groupBy('inward_stock_id')
                          ->get();


            foreach ($inward_stock AS $inward_key=>$inward_value)
            {
                $detail = array('Invoice No' => $inward_value->invoice_no,
                                'Invoice Date' => $inward_value->invoice_date,
                                'Inward Date' => $inward_value->inward_date
                    );
                $dependent_array[] = array(
                    'Module_Name'=> "Inward Stock",
                    'detail' => $detail
                );
            }
        }

       return json_encode(array("Success"=>"True","Data"=>$dependent_array));
    }
}
