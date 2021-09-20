<?php

namespace Retailcore\Products_Kit\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\price_master;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Retailcore\Products_Kit\Models\combo_products_detail;
use Retailcore\Products\Models\product\product_image;
use Auth;
use DB;


class ComboProductsDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('product_id', 'DESC')
            ->where('item_type','=','1')
            ->paginate(10);

        $system_barcode = str_pad(Auth::user()->company_id,10,"0");

        $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');

        $product_max_id++;

        $system_barcode_final = $system_barcode + $product_max_id;
        $supplier_barcode     = 'K'.$system_barcode_final;

        return view('products_kit::addproducts_kit',compact('system_barcode_final','supplier_barcode'));
    }
   

     
    public function viewproducts_kit()
    {
         $product = product::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL)
            ->orderBy('product_id', 'DESC')
            ->where('item_type','=','3')
            ->paginate(10);

            $product_data = array();

        return view('products_kit::viewproducts_kit',compact('product','product_data'));
    }
    public function kitproduct_search(Request $request)
    {

        if($request->search_val !='')
        {

            $json = [];
            $result = product::where('company_id',Auth::user()->company_id)
                ->select('product_name','product_system_barcode','product_id','hsn_sac_code')
                ->where('product_name', 'LIKE', "%$request->search_val%")
                ->where('item_type','=',1)
                ->orWhere('product_system_barcode', 'LIKE', "%$request->search_val%")
                ->orWhere('hsn_sac_code', 'LIKE', "%$request->search_val%")->take(10)->get();

            $sresult = product::select('supplier_barcode','product_name','product_system_barcode')
                ->where('company_id',Auth::user()->company_id)
                ->Where('supplier_barcode', 'LIKE', "%$request->search_val%")->take(10)->get();
               
           

             if(sizeof($result) != 0) 
            {
           
                foreach($result as $productkey=>$productvalue){


                      $json[$productkey]['label'] = $productvalue['product_system_barcode'].'_'.$productvalue['product_name'];
                      $json[$productkey]['product_name'] = $productvalue['product_name'];
                      $json[$productkey]['barcode'] = $productvalue['product_system_barcode'];
                }
            }
           if(sizeof($sresult) != 0)
            {
               foreach($sresult as $sproductkey=>$sproductvalue){

                      $json[$sproductkey]['label'] = $sproductvalue['supplier_barcode'].'_'.$sproductvalue['product_name'];
                      $json[$sproductkey]['product_name'] = $sproductvalue['product_name'];
                      $json[$sproductkey]['barcode'] = $sproductvalue['product_system_barcode'];
                }
            }
            
            return json_encode($json);
        }
        else
        {
          $json = [];
          return json_encode($json);
        }
       
        
    }
    public function kitproduct_detail(Request $request)
    {
      
      $barcode        =  $request->barcode;
      $product_name   =  $request->product_name;

      $query = product::select('product_name','sku_code','hsn_sac_code','product_id','product_system_barcode','supplier_barcode','colour_id','size_id','uqc_id','offer_price','cost_price','selling_price','sell_gst_percent')
               ->with('price_master')
               ->where('product_system_barcode',$barcode)
               ->where('product_name',$product_name);
     

        $result  =  $query->where('company_id',Auth::user()->company_id)
        ->with('colour','size','uqc')->get();

      
          

                   $overallqty =  price_master::where('company_id',Auth::user()->company_id)
                   ->where('product_id','=',$result[0]['product_id'])
                   ->sum('product_qty');
       
      

      return json_encode(array("Success"=>"True","Data"=>$result,"Stock"=>$overallqty));
    }
     public function productkit_create(Request $request)
    {

        $data = $request->all();

        // echo '<pre>';
        // print_r($data);
        // exit;


        // $productdata =  array();

        // parse_str($data['formdata'], $productdata);
        // $productdata = preg_replace('/\s+/', ' ', $productdata);



        $system_barcode = str_pad(Auth::user()->company_id,10,"0");

        $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');

        $product_max_id++;

        if($data['product_id']!='')
        {
            $system_barcode_final = $data['product_system_barcode'];
            $supplier_barcode     = $data['supplier_barcode'];
        }
        else
        {
            $system_barcode_final = $system_barcode + $product_max_id;
            $supplier_barcode     = 'K'.$system_barcode_final;
        }

        

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;
        $created_by = $userId;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

        $inward_type = 1;
        if(isset($inward_type_from_comp) && !empty($inward_type_from_comp) && $inward_type_from_comp['inward_type'] != '')
        {
            $inward_type = $inward_type_from_comp['inward_type'];
        }

        try {
            $product = product::updateOrCreate(
                ['product_id' => $data['product_id'], 'company_id' => $company_id,],
                [
                    'created_by' => $created_by,
                    'company_id' => $company_id,
                    'product_type' => $inward_type,
                    'item_type' => 3,
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
                    'product_system_barcode' => (isset($system_barcode_final) && $system_barcode_final !=" " && $system_barcode_final != '' ? $system_barcode_final : NULL),
                    'supplier_barcode' => (isset($supplier_barcode) && $supplier_barcode !=" " && $supplier_barcode != '' ? $supplier_barcode : NULL),
                    'is_ean' => (isset($data['is_ean']) ? $data['is_ean'] : '0'),
                    'alert_product_qty' => (isset($data['alert_product_qty']) ? $data['alert_product_qty'] : '0'),
                    'product_ean_barcode' => (isset($data['product_ean_barcode']) ? $data['product_ean_barcode'] : '0'),
                    // 'minimum_qty' => (isset($productdata['minimum_qty'])?$productdata['minimum_qty']):''),
                    'sku_code' => (isset($data['sku_code']) ? $data['sku_code'] : ''),
                    'product_code' => (isset($data['product_code']) ? $data['product_code'] : ''),
                    'product_description' => (isset($data['product_description']) ? $data['product_description'] : ''),
                    'hsn_sac_code' => (isset($data['hsn_sac_code']) && !empty($data['hsn_sac_code']) ? $data['hsn_sac_code'] : NULL),
                    'days_before_product_expiry' => (isset($data['days_before_product_expiry']) && $data['days_before_product_expiry'] != '' ? $data['days_before_product_expiry'] : 0),
                    'is_active' => "1"
                ]
            );
           
            if ($product)
            {
                $product_id = $product->product_id;

                if($request->file('product_image'))
                {
                    foreach($request->file('product_image') as $key=>$image)
                    {
                        $image_name = str_replace(' ','_',$data['product_name']).'_'.rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/products'), $image_name);

                        $product_images     =   product_image::updateOrCreate(
                            [
                                'product_id'    => $product_id,
                                'caption'       => $data['imageCaption'][$key],
                                'product_image' => $image_name,
                                'company_id'    => $company_id,
                                'is_active'     => '1',
                                'created_by'    => $created_by,
                            ]
                        );
                    }
                }


           
                if ($data['product_id'] != '')
                {

                    return json_encode(array("Success" => "True", "Message" => "Kit has been successfully updated."));
                } else {
                    return json_encode(array("Success" => "True", "Message" => "Kit has been successfully added.","Data"=>$product_id));
                }
            } else {
                return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));
            }
        }catch (\Exception $e)
        {
            return json_encode(array("Success" => "False", "Message" => $e->getMessage()));

        }
        return back()->withInput();

    }

    public function kit_create(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;


        $created_by = $userId;

      
       // sales_product_detail::where('sales_bill_id',$sales_bill_id)->update(array(
       //      'modified_by' => Auth::User()->user_id,
       //      'updated_at' => date('Y-m-d H:i:s')
       //  ));

    
        $productdetail     =    array();

         foreach($data[0] AS $productkey=>$productvalue)
          {
              
               if($productvalue['product_id']!='')
               {

                      $productdetail['product_id']                      =     $productvalue['product_id'];
                      $productdetail['qty']                             =     $productvalue['qty'];
                      $productdetail['kitproduct_id']                   =     $data[1]['kitproduct_id'];
                      $productdetail['created_by']                      =     Auth::User()->user_id;

          

                $billproductdetail = combo_products_detail::updateOrCreate(
                   ['company_id'=>$company_id,'combo_products_detail_id'=>$productvalue['combo_products_detail_id'],],
                   $productdetail);

              
      }
     
     
            
     }



        if($billproductdetail)
        {
            
           if($data[1]['kitproduct_id'] != '')
          {   
              return json_encode(array("Success"=>"True","Message"=>"Kit successfully Update!","url"=>"viewproducts_kit"));
          }
          else
          {
              return json_encode(array("Success"=>"True","Message"=>"Kit has been successfully added.","url"=>"viewproducts_kit"));
          }

               

           
        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        //return back()->withInput();

    }
     function productskit_fetch_data(Request $request)
    {
        if($request->ajax())
        {
            $data = $request->all();
            $product_data  = array();

            $sort_by = $data['sortby'];
            $sort_type = $data['sorttype'];
            $query = isset($data['query']) ? $data['query']  : '';
            //$query = str_replace(" ", "", $query);
            //$query = str_replace(" ", "%", $query);
            $product = product::where('deleted_at','=',NULL)->where('item_type','=',3)->where('company_id',Auth::user()->company_id);

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
            $system_barcode = str_pad(Auth::user()->company_id,10,"0");
            $product_max_id = product::withTrashed()->where('company_id',Auth::user()->company_id)->get()->max('product_id');
            $product_max_id++;
            $system_barcode_final = $system_barcode + $product_max_id ;

            $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('inward_type')->first();

            $inward_type = $inward_type_from_comp['inward_type'];

            return view('products_kit::viewproducts_kitdata', compact('product','product_data'))->render();
        }
    }

    public function edit_productskit(Request $request)
    {
        $product_id = decrypt($request->product_id);
       
        $product_data = product::where([
            ['product_id','=',$product_id],
            ['company_id',Auth::user()->company_id]])
            ->with('product_images')
            ->with('combo_products_detail','combo_products_detail.product','combo_products_detail.product.size','combo_products_detail.product.colour','combo_products_detail.product.uqc')
            ->with('combo_products_detail.price_master')
            ->with('brand') 
            ->with('category')
            ->with('subcategory') 
            ->with('colour')   
            ->with('size')    
            ->with('uqc')  
            ->first();

      

          $product_data['public_path'] =   $request->root();
         

        return json_encode(array("Success"=>"True","Data"=>$product_data,"url"=>"addproducts_kit"));


    }

  public function view_kitdetail_popup(Request $request)
  {
        $product_data = array();
        if($request->ajax())
        {
            
            $product_data = product::select('*')
            ->where('product_id','=',$request->product_id)
            ->where('company_id',Auth::user()->company_id)
            ->with('product_images')
            ->with('combo_products_detail','combo_products_detail.product','combo_products_detail.product.size','combo_products_detail.product.colour','combo_products_detail.product.uqc')
            ->with('brand') 
            ->with('category')
            ->with('subcategory') 
            ->with('colour')   
            ->with('size')    
            ->with('uqc')  
            ->with('product_images')
            ->get();
            // echo '<pre>';
            // print_r($product_data['combo_products_detail']);
            // exit;
             return view('products_kit::view_kit_popup',compact('product_data'));

         }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\combo_products_detail  $combo_products_detail
     * @return \Illuminate\Http\Response
     */
    public function show(combo_products_detail $combo_products_detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\combo_products_detail  $combo_products_detail
     * @return \Illuminate\Http\Response
     */
    public function edit(combo_products_detail $combo_products_detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\combo_products_detail  $combo_products_detail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, combo_products_detail $combo_products_detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\combo_products_detail  $combo_products_detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(combo_products_detail $combo_products_detail)
    {
        //
    }
}
