<?php

namespace Retailcore\Debit_Note\Http\Controllers\debit_note;
use Retailcore\Debit_Note\Models\debit_note\debit_note;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Retailcore\Debit_Note\Models\debit_note\debit_product_detail;
use Retailcore\Debit_Note\Models\debit_note\debit_note_report_excel;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Products\Models\product\price_master;
use Illuminate\Http\Request;
use Auth;
class DebitProductDetailController extends Controller
{

    public function edit_debit_note(Request $request)
    {

        $debit_note_id = decrypt($request->debit_note_id);

        $debit_note = debit_note::where([
            ['debit_note_id','=',$debit_note_id],
            ['company_id',Auth::user()->company_id]])
            ->with('debit_product_details.product')
            ->with('inward_stock')
            ->with('supplier_gstdetail.supplier_company_info')
            ->select('*')
            ->first();
      $inward_stock_id =  isset($debit_note) && $debit_note != ''?$debit_note['inward_stock_id'] : '';
        $supplier_gst_id =  isset($debit_note) && $debit_note != ''?$debit_note['supplier_gst_id'] : '';
        foreach ($debit_note['debit_product_details'] AS $key=>$value)
        {

            $price = price_master::where('product_id',$value['product_id'])
                ->whereNull('deleted_at')
                ->where('company_id', Auth::user()->company_id)
                ->sum('product_qty');

            $debit_note['debit_product_details'][$key]['in_stock'] = $price;

          $inward_product_detail =   inward_product_detail::select('product_mrp','offer_price','batch_no','inward_product_detail_id','base_price','product_qty','free_qty','pending_return_qty')->where('product_id',$value['product_id'])
                ->whereNull('deleted_at')
                ->where('company_id', Auth::user()->company_id)
                //->where('inward_stock_id',$inward_stock_id)
                ->where('product_id',$value['product_id'])
                ->where('supplier_gst_id',$supplier_gst_id)->first();


            $debit_note['debit_product_details'][$key]['inward_product_detail'] =$inward_product_detail;
        }
        $data = json_encode($debit_note);

        $url = 'debit_note';


        return json_encode(array("Success"=>"True","Data"=>$data,"url"=>$url));
    }

    public function debit_note_report()
    {
        $sort_by = 'debit_product_detail_id';
        $sort_type = 'DESC';
        $debit_product_detail =  debit_product_detail::where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->with('debit_note')
            ->with('product')
            ->orderBy($sort_by, $sort_type)
            ->paginate(10);

       return view('debit_note::debit_note/debit_note_report',compact('debit_product_detail'));
    }


    //FILTER FOR DEBIT NOTE REPORT

    public function debit_no_wise_search_record(Request $request)
    {
        $data = $request->all();
        $sort_by = $data['sortby'];
        $sort_type = $data['sorttype'];
        $query = (isset($data['query']) ? $data['query'] : '');

        $from_date = isset($query['from_date']) ? $query['from_date'] : '';
        $to_date = isset($query['to_date']) ? $query['to_date'] : '';
        $debit_no = isset($query['debit_no']) ? $query['debit_no'] : '';


        $query =  debit_product_detail::where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->with('debit_note')
            ->with('product');


        if ($from_date != '')
        {
            $query->whereHas('debit_note',function ($q) use($from_date,$to_date)
            {
                $q->whereBetween('debit_date', [$from_date,$to_date]);
            });
        }

        if ($debit_no != '')
        {
            $query->whereHas('debit_note',function ($q) use($debit_no)
            {
                $q->whereRaw("debit_notes.debit_no='" . $debit_no . "'");
            });
        }

        $debit_product_detail = $query->orderBy($sort_by,$sort_type)->paginate(10);

        return view('debit_note::debit_note/debit_note_report_data',compact('debit_product_detail'))->render();
    }


    public function debitnote_report_export(Request $request)
    {
        return Excel::download(new debit_note_report_excel($request->from_date,$request->to_date,$request->debit_no),'Debit_note_report.xlsx');
    }


}
