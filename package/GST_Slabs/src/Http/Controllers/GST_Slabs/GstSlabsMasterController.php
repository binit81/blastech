<?php

namespace Retailcore\GST_Slabs\Http\Controllers\GST_Slabs;

use App\Http\Controllers\Controller;
use Retailcore\GST_Slabs\Models\GST_Slabs\gst_slabs_master;
use Illuminate\Http\Request;
use Auth;
use DB;
class GstSlabsMasterController extends Controller
{

    public function index()
    {
        $gst_slabs = gst_slabs_master::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->where('is_active','=','1')
            ->orderBy('gst_slabs_master_id', 'DESC')
            ->paginate(10);
        return view('gst_slab::gst_slabs',compact('gst_slabs'));
    }

    public function gst_slabs_data(Request $request)
    {
        if($request->ajax())
        {
            $gst_slabs = gst_slabs_master::where('company_id',Auth::user()->company_id)
                ->where('is_active','=','1')
                ->where('deleted_at','=',NULL)
                ->orderBy('gst_slabs_master_id', 'DESC')
                ->paginate(10);
            return view('gst_slab::gst_slabs_data',compact('gst_slabs'));
        }
    }

    function gst_slabs_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query'] : '';

            $query = str_replace(" ", "%", $query);
            $gst_slabs = gst_slabs_master::where('gst_slabs_master_id', 'like', '%'.$query['serach'].'%')
                ->orWhere('selling_price_from', 'like', '%'.$query['serach'].'%')
                ->orWhere('selling_price_to', 'like', '%'.$query['serach'].'%')
                ->orWhere('percentage', 'like', '%'.$query['serach'].'%')
                ->orderBy($sort_by, $sort_type)
                ->paginate(10);
            return view('gst_slab::gst_slabs_data', compact('gst_slabs'))->render();
        }
    }

    public function gstslabs_create(request $request)
    {
        $data = $request->all();
        $gstslabsdata =  array();

        parse_str($data['formdata'], $gstslabsdata);
        $gstslabsdata = preg_replace('/\s+/', ' ', $gstslabsdata);
        $company_id = Auth::User()->company_id;

        $created_by = Auth::User()->user_id;

        $price_from = $gstslabsdata['selling_price_from'];
        $price_to = $gstslabsdata['selling_price_to'];
        $percentage = $gstslabsdata['percentage'];

        if($gstslabsdata['gst_slabs_master_id'] == '')
        {
            $gst_slabs = gst_slabs_master::where('company_id', Auth::user()->company_id)
                ->where('is_active', '=', '1')
                ->where('deleted_at', '=', NULL)->get();
        }
        else {
            $gst_slabs = gst_slabs_master::where('company_id', Auth::user()->company_id)
                ->where('gst_slabs_master_id', '!=', $gstslabsdata['gst_slabs_master_id'])
                ->where('is_active', '=', '1')
                ->where('deleted_at', '=', NULL)->get();

        }

        foreach ($gst_slabs AS $key=>$value)
        {
            if(($price_from >= $value['selling_price_from'] && $price_from <= $value['selling_price_to']) ||($price_to >= $value['selling_price_from'] &&  $price_to <= $value['selling_price_to']))
            {
                return json_encode(array("Success"=>"False","Message"=>"This GST Slab already exists!"));
                exit;
            }


            if(($value['selling_price_to'] <= $price_to)  && ($percentage <= $value['percentage']) || ($value['selling_price_to'] >= $price_to && $percentage >= $value['percentage']))
            {
                return json_encode(array("Success"=>"False","Message"=>"Your GST Percentage is not valid according to existing GST Slabs!"));
                exit;
            }
        }

        try{
            DB::beginTransaction();
        $gstslabs = gst_slabs_master::updateOrCreate(
            ['gst_slabs_master_id' => $gstslabsdata['gst_slabs_master_id'],
             'company_id'=>$company_id,],
            [
                'created_by' =>$created_by,
                'company_id'=>$company_id,
                'selling_price_from' => (isset($gstslabsdata['selling_price_from'])?$gstslabsdata['selling_price_from'] : ''),
                'selling_price_to' => (isset($gstslabsdata['selling_price_to'])?$gstslabsdata['selling_price_to'] : ''),
                'percentage' => (isset($gstslabsdata['percentage'])?$gstslabsdata['percentage'] : ''),
                'note' => (isset($gstslabsdata['gst_note'])?$gstslabsdata['gst_note'] : ''),
                'is_active' => "1"
            ]
        );
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }

        if($gstslabs)
        {
            if ($gstslabsdata['gst_slabs_master_id'] != '')
            {
                return json_encode(array("Success"=>"True","Message"=>"GST Slabs has been successfully updated."));
            }
            else
            {
                return json_encode(array("Success"=>"True","Message"=>"GST Slabs has been successfully added."));
            }
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }

    }

    public function gstslab_edit(Request $request)
    {
        $gst_slabs_master_id = decrypt($request->gst_slabs_master_id);

        $gstslabsdata = gst_slabs_master::where([['gst_slabs_masters.gst_slabs_master_id','=',$gst_slabs_master_id],['company_id',Auth::user()->company_id]])
            ->select('gst_slabs_masters.*')
            ->first();

        return json_encode(array("Success"=>"True","Data"=>$gstslabsdata));
    }

    public function gstslabs_delete(Request $request)
    {
        $userId = Auth::User()->user_id;

        try {
            DB::beginTransaction();

            $gstslab_delete = gst_slabs_master::whereIn('gst_slabs_master_id', $request->deleted_id)
                ->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
            DB::commit();
        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }

        if($gstslab_delete)
        {
            return json_encode(array("Success"=>"True","Message"=>"Gst Slabs has been successfully deleted.!"));
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong!"));
        }
    }
}
