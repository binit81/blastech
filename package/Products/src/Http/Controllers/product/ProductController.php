<?php

namespace Retailcore\Products\Http\Controllers\product;
use App\Http\Controllers\Controller;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\DamageProducts\Models\damageproducts\damage_product_detail;
use Retailcore\Debit_Note\Models\debit_note\debit_product_detail;
use Retailcore\PO\Models\purchase_order\purchase_order_detail;
use Retailcore\Products\Models\product\price_master;

use Retailcore\Products\Models\product\brand;
use Retailcore\Products\Models\product\colour;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\product_image;
use Retailcore\Products\Models\product\category;
use Retailcore\Products\Models\product\product_export;
use Retailcore\Products\Models\product\size;
use Retailcore\Products\Models\product\subcategory;
use Retailcore\Products\Models\product\uqc;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Faker\Provider\Barcode;
use function foo\func;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Auth;
use phpDocumentor\Reflection\Types\Null_;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;
use Prophecy\Prophecy\RevealerInterface;
use DB;
ini_set('max_execution_time', 300); //300 seconds = 5 minutes


class  ProductController extends Controller
{
    public function index()
    {
        $product = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('product_id', 'DESC')
            ->where('item_type','=','1')
            ->with('product_image_')
            ->paginate(10);

        foreach ($product AS $key=>$value)
        {
            $inward_product_detail = price_master::where('product_id',$value->product_id)
                ->whereNull('deleted_at')
                ->where('company_id',Auth::user()->company_id)
                ->count();
            $product[$key]['delete_option'] = 1;

            $po_product = purchase_order_detail::where('product_id',$value->product_id)
                ->whereNull('deleted_at')
                ->where('company_id',Auth::user()->company_id)
                ->count();


            if($inward_product_detail > 0 || $po_product > 0)
            {
                $product[$key]['delete_option'] = 0;
            }
        }

        $system_barcode = str_pad(Auth::user()->company_id,10,"0");

        $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');

        $product_max_id++;

        $system_barcode_final = $system_barcode + $product_max_id ;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

        $inward_type = $inward_type_from_comp['inward_type'];

        return view('products::product/product_show',compact('product','system_barcode_final','inward_type'));
    }

    public function get_productImages(Request $request)
    {
        $data = $request->all();

        $result     =   product_image::select('caption','product_image','product_id')->where('product_id',$data['product_id'])->whereNull('deleted_at')
        ->with('products')->orderBy('product_image_id','DESC')->get();

        return json_encode(array("Data"=>$result,"public_path"=>$request->root()));

    }

    public function service_data(Request $request)
    {
        if($request->ajax())
        {
            $service = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('product_id', 'DESC')
            ->where('item_type','=','2')
            ->paginate(10);

            return view('product.service_data',compact('service'));
        }
    }

    public function product_data(Request $request)
    {
        if($request->ajax())
        {
            $product = product::where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('item_type','=','1')
                ->orderBy('product_id', 'DESC')
                ->paginate(10);

            foreach ($product AS $key=>$value)
            {
                $inward_product_detail = price_master::where('product_id',$value->product_id)
                    ->whereNull('deleted_at')
                    ->where('company_id',Auth::user()->company_id)
                    ->count();
                $product[$key]['delete_option'] = 1;

                $po_product = purchase_order_detail::where('product_id',$value->product_id)
                    ->whereNull('deleted_at')
                    ->where('company_id',Auth::user()->company_id)
                    ->count();


                if($inward_product_detail > 0 || $po_product > 0)
                {
                    $product[$key]['delete_option'] = 0;
                }
            }

            $system_barcode = str_pad(Auth::user()->company_id,10,"0");

            $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');
            $product_max_id++;

            $system_barcode_final = $system_barcode + $product_max_id ;

            $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

            $inward_type = $inward_type_from_comp['inward_type'];

            return view('products::product/product_data',compact('product','system_barcode_final','inward_type'));
        }
    }


    function room_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = $data['query'];

            $query = str_replace(" ", "%", $query);
            $service = product::where('product_id', 'like', '%'.$query.'%')
                ->orWhere('supplier_barcode', 'like', '%'.$query.'%')
                ->orWhere('selling_price', 'like', '%'.$query.'%')
                ->orWhere('sell_gst_percent', 'like', '%'.$query.'%')
                ->orWhere('sell_gst_amount', 'like', '%'.$query.'%')
                ->orWhere('product_mrp', 'like', '%'.$query.'%')
                ->orWhere('hsn_sac_code', 'like', '%'.$query.'%')
                ->where('item_type','=','2')
                ->where('supplier_barcode','!=','')
                ->where('deleted_at','=',NULL)
                ->orderBy($sort_by, $sort_type)
                ->paginate(10);

            return view('product.service_data', compact('service'))->render();
        }
    }


    function product_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();

            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';

            //$query = str_replace(" ", "", $query);
            //$query = str_replace(" ", "%", $query);
            $product = product::where('deleted_at','=',NULL)->where('item_type','=',1)->where('company_id',Auth::user()->company_id);

            if(isset($query) && $query != '' && $query['product_name'] != '')
            {
                $product->where('product_name', 'like', '%'.$query['product_name'].'%');
            }
            if(isset($query) && $query != '' && $query['barcode'] != '')
            {
                $product->where('product_system_barcode', 'like', '%'.$query['barcode'].'%');
                $product->orWhere('supplier_barcode', 'like', '%'.$query['barcode'].'%');
            }
            if(isset($query) && $query != '' && $query['brand_id'] != '' && $query['brand_id'] != 0)
            {
                $product->where('brand_id', '=', $query['brand_id']);
            }
            if(isset($query) && $query != '' && $query['category_id'] != '' && $query['category_id'] != 0)
            {
                $product->where('category_id', '=', $query['category_id']);
            }
            if(isset($query) && $query != '' && $query['subcategory_id'] != '' && $query['subcategory_id'] != 0)
            {
                $product->where('subcategory_id', '=', $query['subcategory_id']);
            }
            if(isset($query) && $query != '' && $query['colour_id'] != '' && $query['colour_id'] != 0)
            {
                $product->where('colour_id', '=', $query['colour_id']);
            }
            if(isset($query) && $query != '' && $query['size_id'] != '' && $query['size_id'] != 0)
            {
                $product->where('size_id', '=', $query['size_id']);
            }
            if(isset($query) && $query != '' && $query['uqc_id'] != '' && $query['uqc_id'] != 0)
            {
                $product->where('uqc_id', '=', $query['uqc_id']);
            }
            $product = $product->orderBy($sort_by,$sort_type)->paginate(10);

            foreach ($product AS $key=>$value)
            {
                $inward_product_detail = price_master::where('product_id',$value->product_id)
                    ->whereNull('deleted_at')
                    ->where('company_id',Auth::user()->company_id)
                    ->count();
                $product[$key]['delete_option'] = 1;

                $po_product = purchase_order_detail::where('product_id',$value->product_id)
                    ->whereNull('deleted_at')
                    ->where('company_id',Auth::user()->company_id)
                    ->count();


                if($inward_product_detail > 0 || $po_product > 0)
                {
                    $product[$key]['delete_option'] = 0;
                }
            }

            $system_barcode = str_pad(Auth::user()->company_id,10,"0");
            $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');
            $product_max_id++;
            $system_barcode_final = $system_barcode + $product_max_id ;

            $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

            $inward_type = $inward_type_from_comp['inward_type'];

            return view('products::product/product_data', compact('product','system_barcode_final','inward_type'))->render();
        }
    }


    public function product_create(Request $request)
    {
        $data = $request->all();

        // echo '<pre>'; print_r($data);

        if ($data['type'] == 1) {
            $validate_error = \Validator::make($data,
                [
                    //'product_name' => ['required', Rule::unique('products')->ignore($productdata['product_id'], 'product_id')->whereNull('deleted_at')],
                    'supplier_barcode' => [Rule::unique('products')->ignore($data['product_id'], 'product_id')->whereNull('deleted_at')->whereNotNull('supplier_barcode')],
                ]);
        } else {
            $validate_error = \Validator::make($data,
                [
                    'supplier_barcode' => ['required', Rule::unique('products')->ignore($data['product_id'], 'product_id')->whereNull('deleted_at')],
                ]);
        }

        if ($validate_error->fails()) {
            return json_encode(array("Success" => "False", "status_code" => 409, "Message" => $validate_error->messages()));
            exit;
        }

        $same_supplier_system_barcode = product::where('product_system_barcode', '=', $data['supplier_barcode'])->whereNull('deleted_at')->count();

        if (isset($same_supplier_system_barcode) && $same_supplier_system_barcode > 0) {
            return json_encode(array("Success" => "False", "Message" => "This supplier barcode already exist in product system barcode!"));
            exit;
        }

        $same_system_supplier_barcode = product::where('supplier_barcode', '=', $data['product_system_barcode'])->whereNull('deleted_at')->count();

        if (isset($same_system_supplier_barcode) && $same_system_supplier_barcode > 0) {
            return json_encode(array("Success" => "False", "Message" => "This product system barcode already exist in supplier barcode!"));
            exit;
        }

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;
        $created_by = $userId;

        $inward_type_from_comp = company_profile::where('company_id', Auth::user()->company_id)->select('inward_type')->first();

        $inward_type = 1;
        if (isset($inward_type_from_comp) && !empty($inward_type_from_comp) && $inward_type_from_comp['inward_type'] != '') {
            $inward_type = $inward_type_from_comp['inward_type'];
        }
        $system_barcode_final = '';
        if ($data['product_id'] == '') {
            $system_barcode = str_pad(Auth::user()->company_id, 10, "0");

            $product_max_id = product::withTrashed()->where('company_id', Auth::user()->company_id)->get()->max('product_id');
            $product_max_id++;

            $system_barcode_final = $system_barcode + $product_max_id;
        }

        try {
            DB::beginTransaction();
            $product = product::updateOrCreate(
                ['product_id' => $data['product_id'], 'company_id' => $company_id,],
                [
                    'created_by' => $created_by,
                    'company_id' => $company_id,
                    'product_type' => $inward_type,
                    'item_type' => $data['type'],
                    'product_name' => (isset($data['product_name']) ? $data['product_name'] : ''),
                    'note' => (isset($data['product_note']) ? $data['product_note'] : ''),
                    'brand_id' => (isset($data['brand_id']) && $data['brand_id'] != '0' ? $data['brand_id'] : NULL),
                    'category_id' => (isset($data['category_id']) && $data['category_id'] != '0' ? $data['category_id'] : NULL),
                    'subcategory_id' => (isset($data['subcategory_id']) && $data['subcategory_id'] != '0' ? $data['subcategory_id'] : NULL),
                    'colour_id' => (isset($data['colour_id']) && $data['colour_id'] != '0' ? $data['colour_id'] : NULL),
                    'size_id' => (isset($data['size_id']) && $data['size_id'] != '0' ? $data['size_id'] : NULL),
                    'uqc_id' => (isset($data['uqc_id']) && $data['uqc_id'] != '0' ? $data['uqc_id'] : NULL),
                    'cost_rate' => (isset($data['cost_rate']) ? $data['cost_rate'] : '0'),
                    'cost_price' => (isset($data['cost_price']) ? $data['cost_price'] : '0'),
                    'selling_price' => (isset($data['selling_price']) ? $data['selling_price'] : '0'),
                    'offer_price' => (isset($data['offer_price']) ? $data['offer_price'] : '0'),
                    'product_mrp' => (isset($data['product_mrp']) ? $data['product_mrp'] : '0'),
                    'wholesale_price' => (isset($data['wholesale_price']) ? $data['wholesale_price'] : '0'),
                    'cost_gst_percent' => (isset($data['cost_gst_percent']) ? $data['cost_gst_percent'] : '0'),
                    'cost_gst_amount' => (isset($data['cost_gst_amount']) ? $data['cost_gst_amount'] : '0'),
                    'extra_charge' => (isset($data['extra_charge']) ? $data['extra_charge'] : '0'),
                    'profit_percent' => (isset($data['profit_percent']) ? $data['profit_percent'] : '0'),
                    'profit_amount' => (isset($data['profit_amount']) ? $data['profit_amount'] : '0'),
                    'sell_gst_percent' => (isset($data['sell_gst_percent']) ? $data['sell_gst_percent'] : '0'),
                    'sell_gst_amount' => (isset($data['sell_gst_amount']) ? $data['sell_gst_amount'] : '0'),
                    //'product_system_barcode' => (isset($data['product_system_barcode']) ? $data['product_system_barcode'] : ''),
                    'product_system_barcode' => (isset($system_barcode_final) && $system_barcode_final != '' ? $system_barcode_final : $data['product_system_barcode']),
                    'supplier_barcode' => (isset($data['supplier_barcode']) && $data['supplier_barcode'] != " " && $data['supplier_barcode'] != '' ? $data['supplier_barcode'] : NULL),
                    'is_ean' => (isset($data['is_ean']) ? $data['is_ean'] : '0'),
                    'alert_product_qty' => (isset($data['alert_product_qty']) ? $data['alert_product_qty'] : '0'),
                    'product_ean_barcode' => (isset($data['product_ean_barcode']) ? $data['product_ean_barcode'] : '0'),
                    // 'minimum_qty' => (isset($data['minimum_qty'])?$data['minimum_qty']):''),
                    'sku_code' => (isset($data['sku_code']) ? $data['sku_code'] : ''),
                    'product_code' => (isset($data['product_code']) ? $data['product_code'] : ''),
                    'product_description' => (isset($data['product_description']) ? $data['product_description'] : ''),
                    'hsn_sac_code' => (isset($data['hsn_sac_code']) && !empty($data['hsn_sac_code']) ? $data['hsn_sac_code'] : NULL),
                    'days_before_product_expiry' => (isset($data['days_before_product_expiry']) && $data['days_before_product_expiry'] != '' ? $data['days_before_product_expiry'] : 0),
                    'is_active' => "1"
                ]
            );


            if ($product) {
                $product_id = $product->product_id;

                if ($request->file('product_image')) {
                    foreach ($request->file('product_image') as $key => $image) {
                        $image_name = str_replace(' ', '_', $data['product_name']) . '_' . rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path(PRODUCT_IMAGE_URL_CONTROLLER), $image_name);

                        $product_images = product_image::updateOrCreate(
                            [
                                'product_id' => $product_id,
                                'caption' => $data['imageCaption'][$key],
                                'product_image' => $image_name,
                                'company_id' => $company_id,
                                'is_active' => '1',
                                'created_by' => $created_by,
                            ]
                        );
                    }
                }
            }
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            return json_encode(array("Success" => "False", "Message" => $e->getMessage()));
        }
        catch (Exception $e)
        {
            return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
        }
        if ($data['product_id'] != '')
        {

            return json_encode(array("Success" => "True", "Message" => "Product has been successfully updated."));
        }
        else
        {
            return json_encode(array("Success" => "True", "Message" => "Product has been successfully added."));
        }
    }


    public function product_edit(Request $request)
    {

        $product_id = decrypt($request->product_id);
        $productdata = product::select('products.*')
        ->where([['products.product_id','=',$product_id],['company_id',Auth::user()->company_id]])->with('product_images')->first();

        return json_encode(array("Success"=>"True","Data"=>$productdata,"public_path"=>$request->root()));
    }

    public function ProductremovePicture(Request $request)
    {
        $data               =   $request->all();
        $product_image_id   =   $data['product_image_id'];

        $result         =   product_image::select('product_image')->where('product_image_id',$product_image_id)->get();
        $image_path     =   public_path(PRODUCT_IMAGE_URL_CONTROLLER).'/'.$result[0]->product_image;

        @unlink($image_path);   // REMOVE IMAGE FROM FOLDER
        
        $query  =   product_image::where('product_image_id', $product_image_id)
        ->update([
           'deleted_by' => Auth::User()->user_id,
           'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return json_encode(array("Message"=>"product picture removed successfully","picture"=>'empty'));
    }


    public function product_delete(request $request)
    {
        $userId = Auth::User()->user_id;


        $existing_product = price_master::whereIn('product_id',$request->deleted_id)->whereNull('deleted_at')->where('company_id',Auth::User()->company_id)->count();

        if(isset($existing_product) && $existing_product > 0)
        {
            return json_encode(array("Success"=>"False","Message"=>"Unable to delete product because it's in use"));
        }
        else {

            try {
                DB::beginTransaction();

                $product_delete = product::whereIn('product_id', $request->deleted_id)->update([
                    'deleted_by' => $userId,
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                DB::commit();
            }catch (\Illuminate\Database\QueryException $e)
            {
                DB::rollback();
                return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
            }

            if ($product_delete) {
                return json_encode(array("Success" => "True", "Message" => "Product has been successfully deleted.!"));
            } else {
                return json_encode(array("Success" => "False", "Message" => "Something Went Wrong!"));
            }
        }
    }


    public function product_check(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;
        $created_by = $userId;

        $exist = array();
        $record = [];

        $count = 0;
        if($data != '' && !empty($data))
        {
            if(isset($data['confirm']) && $data['confirm'] == 1)
            {
                foreach ($data['formdata'] AS $key=>$value)
                {
                        if ($value['Size'] != '') {
                            if (!size::where('size_name', $value['Size'])->exists())
                            {
                                size::updateOrCreate(
                                    ['size_id' => '', 'company_id' => $company_id,
                                    ],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'size_name' => $value['Size'],
                                        'is_active' => '1',
                                    ]
                                );
                            }
                        }
                        if ($value['Colour'] != '') {
                            if (!colour::where('colour_name', $value['Colour'])->exists()) {
                                colour::updateOrCreate(
                                    ['colour_id' => '', 'company_id' => $company_id,
                                    ],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'colour_name' => $value['Colour'],
                                        'is_active' => '1',
                                    ]
                                );

                            }
                        }
                        if ($value['Category'] != '') {
                            if (!category::where('category_name', $value['Category'])->exists()) {
                                category::updateOrCreate(
                                    ['category_id' => '',
                                        'company_id' => $company_id,
                                    ],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'category_name' => $value['Category'],
                                        'is_active' => '1',
                                    ]
                                );
                            }
                        }
                        if ($value['Sub category'] != '') {
                            if (!subcategory::where('subcategory_name', $value['Sub category'])->exists()) {

                                $category_id = category::select('category_id')->where('category_name', $value['Category'])->whereNull('deleted_at')->get();
                                $subcategory = subcategory::updateOrCreate(
                                    ['subcategory_id' => '', 'company_id' => $company_id,
                                    ],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'category_id' => $category_id[0]['category_id'],
                                        'subcategory_name' => $value['Sub category'],
                                        'is_active' => '1',
                                    ]
                                );
                            }
                        }
                        if ($value['Brand'] != '') {
                            if (!brand::where('brand_type', $value['Brand'])->exists()) {
                                $brand = brand::updateOrCreate(
                                    ['brand_id' => '', 'company_id' => $company_id,
                                    ],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'brand_type' => $value['Brand'],
                                        'is_active' => '1',
                                    ]
                                );
                            }
                        }

                    $brand_id = 0;
                    if($value['Brand'] != '')
                    {
                    $brand = brand::select('brand_id')->where('brand_type', $value['Brand'])->first();

                    if(isset($brand) && $brand != '')
                    {
                        $brand_id = $brand['brand_id'];
                    }
                    }
                    $category_id = 0;
                    if($value['Category'] != '') {
                        $category = category::select('category_id')->where('category_name', $value['Category'])->whereNull('deleted_at')->first();

                        if (isset($category) && $category != '')
                        {
                            $category_id = $category['category_id'];
                        }
                    }
                    $subcategory_id = 0;
                    if($value['Sub category'] != '') {
                        $subcategory = subcategory::select('subcategory_id')->where('subcategory_name', $value['Sub category'])->whereNull('deleted_at')->first();

                        if (isset($subcategory) && $subcategory != '') {
                            $subcategory_id = $subcategory['subcategory_id'];
                        }
                    }
                    $colour_id = 0;
                    if($value['Colour'] != '') {
                        $colour = colour::select('colour_id')->where('colour_name', $value['Colour'])->whereNull('deleted_at')->first();

                        if (isset($colour) && $colour != '') {
                            $colour_id = $colour['colour_id'];
                        }
                    }
                    $size_id = 0;
                    if($value['Size'] != '') {
                        $size = size::select('size_id')->where('size_name', $value['Size'])->whereNull('deleted_at')->first();

                        if (isset($size) && $size != '') {
                            $size_id = $size['size_id'];
                        }
                    }
                    $uqc_id = 0;
                    if($value['UQC'] != '') {
                        $uqc = uqc::select('uqc_id')->where('uqc_shortname', $value['UQC'])->whereNull('deleted_at')->first();

                        if (isset($uqc) && $uqc != '') {
                            $uqc_id = $uqc['uqc_id'];
                        }
                    }


                    $system_barcode = str_pad(Auth::user()->company_id,10,"0");

                    $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');
                    $product_max_id++;

                    $system_barcode_final = $system_barcode + $product_max_id ;


                    //get tax type,tax title and tax currency from company profile and check cost gst % and sell gst % tax title wise

                    $tax_info = company_profile::select('tax_type','tax_title')->where('company_id',Auth::user()->company_id)->get()->first();

                    $cost_tax_label = 'Cost gst %';
                    $sell_tax_label = 'Sell gst %';
                    if($tax_info['tax_type'] == 1)
                    {
                        $cost_tax_label = 'Cost '.$tax_info['tax_title']. ' %';
                        $sell_tax_label = 'Sell '.$tax_info['tax_title']. ' %';
                    }


                    $cost_gst = number_format(($value['Base price/cost rate'] * $value[$cost_tax_label]) / (100),4);
                    $cost_gst_amt = str_replace(',', '', $cost_gst);

                    $sell_gst = number_format(($value['Offer price'] * $value[$sell_tax_label]) /(100 + $value[$sell_tax_label]),4);
                    $sell_gst_amt = str_replace(',', '', $sell_gst);

                    $selling_prc = number_format($value['Offer price'] - ($sell_gst_amt),4);
                    $selling_price = str_replace(',', '', $selling_prc);

                    $cost_prc = number_format($value['Base price/cost rate'] + $cost_gst_amt,4);
                    $cost_price = str_replace(',', '', $cost_prc);

                    $profit_amt = number_format($selling_price - $value['Base price/cost rate'],4);
                    $profit_amount = str_replace(',', '', $profit_amt);

                    $profit_per = number_format(($profit_amount * (100)) /($value['Base price/cost rate']),4);
                    $profit_percent = str_replace(',', '', $profit_per);

                    /*if($value['Barcode'] == '')
                    {*/
                        if($value['Barcode'] != '')
                        {

                            $barcode = $value['Barcode'];
                            $product = product::select('product_id','product_system_barcode')
                                ->where('supplier_barcode','=',$barcode)
                                ->orWhere('product_system_barcode','=',$barcode)
                                ->withTrashed()->first();


                            if(isset($product) && $product['product_id'] != '')
                            {
                                product::where('product_id',$product['product_id'])
                                    ->where('company_id', Auth::user()->company_id)
                                    ->withTrashed()
                                    ->update(array(
                                        'deleted_at' => NULL,
                                        'deleted_by' => NULL
                                    ));

                                $product_id = $product['product_id'];
                                $data['formdata'][$key]['product_id'] = $product->product_id;
                                $product_system_barcode = $product['product_system_barcode'];
                            }
                            else
                            {
                                $product_id = '';
                                $product_system_barcode = $system_barcode_final;
                            }
                        }
                        else
                        {
                            $product_id = '';
                            $product_system_barcode = $system_barcode_final;
                        }

                        if($product_id == '') {
                            try {
                                DB::beginTransaction();
                                $product = product::updateOrCreate(
                                    ['product_id' => $product_id, 'company_id' => $company_id,],
                                    [
                                        'created_by' => $created_by,
                                        'company_id' => $company_id,
                                        'product_type' => $data['product_type'],
                                        'item_type' => 1,
                                        'product_name' => (isset($value['Product name']) ? $value['Product name'] : ''),
                                        'brand_id' => (isset($brand_id) && $brand_id != '0' ? $brand_id : NULL),
                                        'category_id' => (isset($category_id) && $category_id != '0' ? $category_id : NULL),
                                        'subcategory_id' => (isset($subcategory_id) && $subcategory_id != '0' ? $subcategory_id : NULL),
                                        'colour_id' => (isset($colour_id) && $colour_id != '0' ? $colour_id : NULL),
                                        'size_id' => (isset($size_id) && $size_id != '0' ? $size_id : NULL),
                                        'uqc_id' => (isset($uqc_id) && $uqc_id != '0' ? $uqc_id : NULL),
                                        'cost_rate' => (isset($value['Base price/cost rate']) ? $value['Base price/cost rate'] : '0'),
                                        'cost_price' => (isset($cost_price) ? $cost_price : '0'),
                                        'selling_price' => (isset($selling_price) ? $selling_price : '0'),
                                        'offer_price' => (isset($value['Offer price']) ? $value['Offer price'] : '0'),
                                        'product_mrp' => (isset($value['Product mrp']) ? $value['Product mrp'] : '0'),
                                        'wholesale_price' => (isset($value['wholesale price']) ? $value['wholesale price'] : '0'),
                                        'cost_gst_percent' => (isset($value[$cost_tax_label]) ? $value[$cost_tax_label] : '0'),
                                        'cost_gst_amount' => (isset($cost_gst_amt) ? $cost_gst_amt : '0'),
                                        'profit_percent' => (isset($profit_percent) ? $profit_percent : '0'),
                                        'profit_amount' => (isset($profit_amount) ? $profit_amount : '0'),
                                        'sell_gst_percent' => (isset($value[$sell_tax_label]) ? $value[$sell_tax_label] : '0'),
                                        'sell_gst_amount' => (isset($sell_gst_amt) ? $sell_gst_amt : '0'),
                                        'product_system_barcode' => $product_system_barcode,
                                        'supplier_barcode' => (isset($value['Barcode']) && $value['Barcode'] != ' ' ? $value['Barcode'] : NULL),
                                        'is_ean' => (isset($value['is_ean']) ? $value['is_ean'] : '0'),
                                        'alert_product_qty' => (isset($value['Alert product qty']) ? $value['Alert product qty'] : '0'),
                                        'product_ean_barcode' => (isset($value['product ean barcode']) ? $value['product ean barcode'] : '0'),
                                        // 'minimum_qty' => (isset($value['minimum_qty'])?$value['minimum_qty']):''),
                                        'sku_code' => (isset($value['SKU']) ? $value['SKU'] : ''),
                                        'product_code' => (isset($value['Product code']) ? $value['Product code'] : ''),
                                        'product_description' => (isset($value['Product description']) ? $value['Product description'] : ''),
                                        'hsn_sac_code' => (isset($value['HSN']) ? $value['HSN'] : ''),
                                        'days_before_product_expiry' => (isset($value['Days before product expiry']) && $value['Days before product expiry'] != '' ? $value['Days before product expiry'] : 0),
                                        'is_active' => "1"
                                    ]);


                                $data['formdata'][$key]['product_id'] = $product->product_id;
                                DB::commit();
                            } catch (\Illuminate\Database\QueryException $e) {
                                DB::rollback();
                                return json_encode(array("Success"=>"False","Message"=>$e->getMessage()));
                            }
                        }

                    /*}
                    else
                    {
                        $barcode = $value['Barcode'];
                        $product =  product::select('product_id')->where('supplier_barcode','LIKE',"%$barcode%")
                            ->orWhere('product_system_barcode','LIKE',"%$barcode%")
                            ->whereNull('deleted_at')->first();


                        $data['formdata'][$key]['product_id'] =  $product['product_id'];
                    }*/
                }

                return json_encode(array("Success"=>"True","Message"=>"Product Created Successfully!","Data"=>$data));
            }
            else {
                foreach ($data AS $key => $value)
                {

                    /*if($value['Barcode'] == '')
                    {*/
                        if($value['Barcode'] == '')
                        {
                            $exist['product'][$key] = $value['Product name'];
                        }
                        else
                        {
                             $barcode = $value['Barcode'];
                            if(!product::where('supplier_barcode','=',$barcode)
                                ->orWhere('product_system_barcode','=',$barcode)
                                ->whereNull('deleted_at')->exists())
                            {
                                $exist['product'][$key] = $value['Product name'];
                            }
                        }


                        if($value['UQC'] != '')
                        {
                            if (!uqc::where('uqc_shortname', $value['UQC'])->exists())
                            {
                                return json_encode(array("Success" => "False", "Message" =>"".$value['UQC']."  UQC not found"));
                                exit;
                            }
                        }
                       // $exist['product'][$key] = $value['Product Name'];

                        if ($value['Brand'] != '') {
                            if (!brand::where('brand_type', $value['Brand'])->exists()) {
                                $exist['brand'][$key] = $value['Brand'];
                            }
                        }
                        if ($value['Category'] != '') {
                            if (!category::where('category_name', $value['Category'])->exists()) {
                                $exist['category'][$key] = $value['Category'];
                            }
                        }
                        if ($value['Sub category'] != '') {
                            if (!subcategory::where('subcategory_name', $value['Sub category'])->exists()) {
                                $exist['sub category'][$key] = $value['Sub category'];
                            }
                        }
                        if ($value['Colour'] != '') {
                            if (!colour::where('colour_name', $value['Colour'])->exists()) {
                                $exist['colour'][$key] = $value['Colour'];
                            }
                        }
                        if ($value['Size'] != '') {
                            if (!size::where('size_name', $value['Size'])->exists()) {
                                $exist['size'][$key] = $value['Size'];
                            }
                        }
                        if ($value['UQC'] != '')
                        {
                            if (!uqc::where('uqc_name', $value['UQC'])->exists()) {
                                $exist['UQC'][$key] = $value['UQC'];
                            }
                        }
                  /*  }*/
                    /*else
                    {
                        $barcode = $value['Barcode'];
                        /*$check_product_valid =  product::select('product_id')->where('supplier_barcode','LIKE',"%$barcode%")
                          ->orWhere('product_system_barcode','LIKE',"%$barcode%")
                           ->whereNull('deleted_at')->first();

                      if(!isset($check_product_valid) && $check_product_valid == '' && !isset($check_product_valid['product_id']))
                      {
                          return json_encode(array("Success"=>"False","Message"=>"There are no product available whose supplier barcode or system barcode match with '".$barcode."' !"));
                          exit;
                      }


                        if($value['UQC'] != '')
                        {
                            if (!uqc::where('uqc_shortname', $value['UQC'])->exists())
                            {

                                return json_encode(array("Success" => "False", "Message" =>"".$value['UQC']." not found"));
                                exit;
                            }

                        }
                    }*/
                    }
                array_push($record, $exist);

                return json_encode(array("Success" => "True", "Data" => $record));
            }
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"No Row!"));
        }

    }

    public function inward_product_detail(Request $request)
    {
        $data = $request->all();

      //  isset($data['supplier_barcode']) && $data['supplier_barcode'] != null ? $data['supplier_barcode'] : '';

        $product = product::select('product_id','product_system_barcode','supplier_barcode', 'product_name','hsn_sac_code')
            ->where('product_id',$data['product_id'])
            ->where('company_id',Auth::user()->company_id)
            ->WhereNull('deleted_at')->get();

        return json_encode(array("Success"=>"True","Data"=>$product));

    }

    //for get existing supplier barcode data

   /* Public function get_existing_product_detail(Request $request)
    {
        $data = $request->all();

        if(isset($data) && isset($data['barcode']) && $data['barcode'] != '')
        {
            $product_data = product::where('supplier_barcode',$data['barcode'])
                ->where('company_id',Auth::user()->company_id)
                ->WhereNull('deleted_at')->get();

            return json_encode(array("Success"=>"True","Data"=>$product_data));
        }
        else
        {
            return json_encode(array("Success"=>"True","Data"=>''));
        }
    }*/


   public function product_export(Request $request)
    {
        return Excel::download(new product_export($request->product_name,$request->barcode,$request->brand_id,$request->category_id,$request->subcategory_id,$request->colour_id,$request->size_id,$request->uqc_id),'Products_data.xlsx');
    }

    //This function is used for search product name
    public function product_name_search(Request $request)
    {
        $result = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->where('product_name','LIKE', '%'.$request->search_val.'%')
            ->select('product_name')
            ->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    //This function is used for search BARCODE
    public function product_barcode_search(Request $request)
    {
        $result = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->where('product_system_barcode', 'like', '%'.$request->search_val.'%')
            ->orWhere('supplier_barcode', 'like', '%'.$request->search_val.'%')
            ->select('supplier_barcode','product_system_barcode')
            ->get();

        return json_encode(array("Success"=>"True","Data"=>$result));
    }


    //FOR GETTING DEPENDENT RECORD

    public function product_dependency(Request $request)
    {
        $product_id = decrypt($request->id);

        $dependent_array = [];

        //$product_detail = product::select('product_name')->where('company_id',Auth::User()->company_id);

            //GET PRODUCT DEPEDENCY FROM INWARD STOCK
            $inward_depedency = inward_product_detail::where('company_id',Auth::User()->company_id)
                ->whereNull('deleted_at')
                ->where('product_id',$product_id)
                ->with('inward_stock')
                ->groupBy('batch_no','product_id')->get();

            foreach ($inward_depedency AS $key=>$value)
            {
                $detail = array('Invoice No' => $value->inward_stock->invoice_no);
                $dependent_array[] = array(
                  'Module_Name'=> "Inward Stock",
                  'detail' => $detail,
                  'created_at' => $value->inward_stock->created_at,
                  'updated_at' => $value->inward_stock->updated_at
                );
            }
            //END OF INWARD STOCK DEPENDENCY

            //FOR GETTING PRODUCT DEPENDENCT FROM PURCHASE ORDER
            $po_dependency = purchase_order_detail::where('company_id',Auth::User()->company_id)
                ->whereNull('deleted_at')
                ->where('product_id',$product_id)
                ->with('purchase_order')
                ->groupBy('purchase_order_id','product_id')->get();


            foreach ($po_dependency AS $po_key=>$po_value)
            {
                $detail = array('PO No' => $po_value->purchase_order->po_no);
                $dependent_array[] = array(
                    'Module_Name'=> "Purchase Order",
                    'detail' => $detail,
                    'created_at' => $po_value->purchase_order->created_at,
                    'updated_at' => $po_value->purchase_order->updated_at
                );
            }
            //END OF GETTING PRODUCT DEPENDCY FROM PURCHASE ORDER


            //FOR GETTING PRODUCT DEPENDENCT FROM DEBIT NOTE
            $debit_dependency = debit_product_detail::where('company_id',Auth::User()->company_id)
                ->whereNull('deleted_at')
                ->where('product_id',$product_id)
                ->with('debit_note')
                ->groupBy('debit_note_id','product_id')->get();


            foreach ($debit_dependency AS $debit_key=>$debit_value)
            {
                $detail = array('Debit No' => $debit_value->debit_note->debit_no);
                $dependent_array[] = array(
                    'Module_Name'=> "Debit Note",
                    'detail' => $detail,
                    'created_at' => $debit_value->debit_note->created_at,
                    'updated_at' => $debit_value->debit_note->updated_at
                );
            }
            //END OF GETTING PRODUCT DEPENDCY FROM DEBIT NOTE


            //FOR GETTING PRODUCT DEPENDENCT FROM DAMAGE USED
            $damage_dependency = damage_product_detail::where('company_id',Auth::User()->company_id)
                ->whereNull('deleted_at')
                ->where('product_id',$product_id)
                ->with('damage_product.damage_types')
                ->with('damage_types')
                ->groupBy('damage_product_id','product_id')->get();


            foreach ($damage_dependency AS $damage_key=>$damage_value)
            {
                 $detail = array('Damage No' => $damage_value->damage_product->damage_no,
                     'Damage Type' => $damage_value->damage_product->damage_types->damage_type);
                $dependent_array[] = array(
                    'Module_Name'=> "Damage",
                    'detail' => $detail,
                    'created_at' => $damage_value->damage_product->created_at,
                    'updated_at' => $damage_value->damage_product->updated_at
                );
            }

            //END OF GETTING PRODUCT DEPENDCY FROM DEBIT NOTE

        return json_encode(array("Success"=>"True","Data"=>$dependent_array));

    }
}
