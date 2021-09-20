<?php

namespace Retailcore\Debit_Note\Http\Controllers\debit_note;
use Retailcore\Debit_Note\Models\debit_note\debit_note;
use App\Http\Controllers\Controller;

use Retailcore\Debit_Note\Models\debit_note\view_debit_note;
use Retailcore\Debit_Note\Models\debit_note\debit_product_detail;
use Illuminate\Http\Request;
use Auth;

class ViewDebitNoteController extends Controller
{

    public function index()
    {
       $debit_note = debit_note::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('debit_note_id', 'DESC')
            ->with('inward_stock')
            ->paginate(10);

       return view('debit_note::debit_note/view_debit_note',compact('debit_note'));
    }


    public function view_debit_detail(Request $request)
    {
        $debit_note_id = decrypt($request->debit_note_id);

        $debit_detail = debit_note::where('debit_note_id','=',$debit_note_id)
            ->where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->with('debit_product_details.product')
            ->with('supplier_gstdetail')
            ->get();

        $data = json_encode($debit_detail);

        return json_encode(array("Success"=>"True","Data"=>$data));

    }


    public function debit_note_data(Request $request)
    {
        if($request->ajax())
        {
            $debit_note = debit_note::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->orderBy('debit_note_id', 'DESC')
                ->with('inward_stock')
                ->paginate(10);


            return view('debit_note::debit_note/view_debit_note_data', compact('debit_note'))->render();
        }
    }


    function debit_note_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query'] : '';

            $query = str_replace(" ", "%", $query);

            $debit_note = debit_note::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->orderBy('debit_note_id', 'DESC')
                ->with('inward_stock');

                if($query != '')
                {
                    if ($query['debit_no'] != '') {
                        $debit_note = debit_note::where('debit_no', 'like', "%".$query['debit_no']."%");
                    }
                    if ($query['supplier_gst_id'] != '') {
                        $debit_note = debit_note::where('supplier_gst_id', '=', $query['supplier_gst_id']);
                    }
                }

               $debit_note =  $debit_note->orderBy($sort_by, $sort_type)->paginate(10);

            return view('debit_note::debit_note/view_debit_note_data', compact('debit_note'))->render();
        }
    }
}
