<?php

namespace Retailcore\PO\Http\Controllers\Purchase_order;
use App\Http\Controllers\Controller;


use Retailcore\Products\Models\product\product;


use Retailcore\PO\Models\purchase_order\purchase_order;
use Retailcore\PO\Models\purchase_order\purchase_order_detail;
use Illuminate\Http\Request;
use Auth;
class PurchaseOrderDetailController extends Controller
{

    public function view_po_detail(Request $request)
    {
        $purchase_order_id = decrypt($request->purchase_order_id);

        $purchase_order_detail = purchase_order::where('purchase_order_id','=',$purchase_order_id)
         ->where('company_id',Auth::user()->company_id)
         ->where('deleted_at','=',NULL)
         ->with('purchase_order_detail.product.uqc')
         ->with('purchase_order_detail.product.size')
         ->with('supplier_gstdetail')
         ->get();

        $data = json_encode($purchase_order_detail);

        return json_encode(array("Success"=>"True","Data"=>$data));

    }


    public function create()
    {
        //
    }


}
