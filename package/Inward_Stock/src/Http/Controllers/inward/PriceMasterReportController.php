<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Inward_Stock\Models\inward\price_master_report;
use Retailcore\Products\Models\product\price_master;
use function foo\func;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class PriceMasterReportController extends Controller
{

    public function index()
    {

        $sort_by = 'updated_at';
        $sort_type = 'desc';

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

        $inward_type = 1;
        if(isset($inward_type_from_comp) && !empty($inward_type_from_comp) && $inward_type_from_comp['inward_type'] != '')
        {
            $inward_type = $inward_type_from_comp['inward_type'];
        }

        $price_master = price_master::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->with('product')
            ->orderBy($sort_by, $sort_type)->paginate(10);

        return view('inward_stock::inward/price_master_report',compact('price_master','inward_type'));
    }

    public function price_master_record(Request $request)
    {
        $data = $request->all();


        $sort_by = $data['sortby'];
        $sort_type = $data['sorttype'];
        $query = (isset($data['query']) ? $data['query'] : '');

        //$query = str_replace(" ", "", $query);

        if($request->ajax())
        {
            $price_master = price_master::where('company_id', Auth::user()->company_id)->with('product');

            if(isset($query) && $query != '' && $query['barcode'] != '')
            {
                $price_master->whereHas('product',function ($q) use($query)
                {
                    $q->where('product_system_barcode', 'like', '%'.$query['barcode'].'%');
                    $q->orWhere('supplier_barcode', 'like', '%'.$query['barcode'].'%');
                });
            }
            if(isset($query) && $query != '' && $query['product_name'] != '')
            {
                $price_master->whereHas('product',function ($q) use($query){
                    $q->where('product_name', 'like', '%'.$query['product_name'].'%');
                });
            }

            $price_master = $price_master->orderBy($sort_by, $sort_type)->paginate(10);

            $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

            $inward_type = 1;
            if(isset($inward_type_from_comp) && !empty($inward_type_from_comp) && $inward_type_from_comp['inward_type'] != '')
            {
                $inward_type = $inward_type_from_comp['inward_type'];
            }

            return view('inward_stock::inward/price_master_report_data', compact('price_master','inward_type'))->render();
        }
    }


}
