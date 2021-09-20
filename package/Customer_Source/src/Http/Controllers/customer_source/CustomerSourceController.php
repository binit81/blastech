<?php

namespace Retailcore\Customer_Source\Http\Controllers\customer_source;
use App\Http\Controllers\Controller;

use Retailcore\Customer_Source\Models\customer_source\customer_source;
use Retailcore\Customer\Models\customer\customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use DB;
class CustomerSourceController extends Controller
{

    public function index()
    {
        $customer_source = customer_source::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('customer_source_id', 'DESC')
            ->paginate(10);

        return view('customer_source::customer_source_show',compact('customer_source'));
    }


    public function customer_source_create(Request $request)
    { $data = $request->all();

        $source_data =  array();

        parse_str($data['formdata'], $source_data);

        $source_data = preg_replace('/\s+/', ' ', $source_data);

        $validate_error = \Validator::make($source_data,
            [
                'source_name' => [Rule::unique('customer_sources')->ignore($source_data['customer_source_id'], 'customer_source_id')->whereNull('deleted_at')],
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
            $source = customer_source::updateOrCreate(
                ['customer_source_id' => $source_data['customer_source_id'],
                 'company_id'=>$company_id,],
                [
                    'created_by' =>$created_by,
                    'company_id'=>$company_id,
                    'source_name' => (isset($source_data['source_name'])?$source_data['source_name'] : ''),
                    'note' => (isset($source_data['customer_source_note'])?$source_data['customer_source_note'] : NULL),
                    'is_active' => "1"
                ]
            );
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }
        if($source)
            {
                if ($source_data['customer_source_id'] != '')
                {

                    return json_encode(array("Success"=>"True","Message"=>"Source has been successfully updated."));
                }
                else
                {
                    return json_encode(array("Success"=>"True","Message"=>"Source has been successfully added."));
                }
            }
            else
            {
                return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
            }
    }



    public function source_data(Request $request)
    {
        if($request->ajax())
        {
            $customer_source = customer_source::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->orderBy('customer_source_id', 'DESC')
                ->paginate(10);

            return view('customer_source::customer_source_data', compact('customer_source'))->render();

        }
    }

    public function customer_source_edit(Request $request)
    {
        $customer_source_id = decrypt($request->customer_source_id);

        $customer_source = customer_source::where([
            ['customer_source_id','=',$customer_source_id],
            ['company_id',Auth::user()->company_id]])
            ->select('*')
            ->first();

        return json_encode(array("Success"=>"True","Data"=>$customer_source));
    }




    public function customer_source_delete(Request $request)
    {
        $userId = Auth::User()->user_id;

     $existing_source = customer::whereIn('customer_source_id',$request->deleted_id)->whereNull('deleted_at')->count();
        if(isset($existing_source) && $existing_source > 0)
        {
            return json_encode(array("Success"=>"False","Message"=>"Unable to delete source because it's in use"));
            exit;
        }
        else {

            $source_delete = customer_source::whereIn('customer_source_id', $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
        }

        if($source_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Source has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
        }
    }


    function customer_source_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = $data['query'];

            $query = str_replace(" ", "%", $query);

            $customer_source = customer_source::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL);


            if(isset($query) && $query != '' && $query['source_name'] != '')
            {
                $customer_source->where('source_name', 'like', '%'.$query['source_name'].'%');
            }

            $customer_source = $customer_source->orderBy($sort_by,$sort_type)->paginate(10);

            return view('customer_source::customer_source_data', compact('customer_source'))->render();
        }
    }

}
