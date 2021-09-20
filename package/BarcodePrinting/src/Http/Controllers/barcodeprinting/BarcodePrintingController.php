<?php

namespace Retailcore\BarcodePrinting\Http\Controllers\barcodeprinting;

// use App\BarcodePrinting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Retailcore\Inward_Stock\Models\inward\inward_product_detail;
use Retailcore\Inward_Stock\Models\inward\inward_stock;
use Retailcore\Products\Models\product\product;
use Retailcore\Products\Models\product\colour;
use Retailcore\Products\Models\product\category;
use Retailcore\Products\Models\product\subcategory;
use Retailcore\Products\Models\product\brand;
use Retailcore\Products\Models\product\size;
use Retailcore\BarcodePrinting\Models\barcodeprinting\barcode_sheet;
use App\User;
use Retailcore\BarcodePrinting\Models\barcodeprinting\barcode_template;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Milon\Barcode\DNS1D;
use DB;

class BarcodePrintingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $barcode_sheet          =   barcode_sheet::where('is_active','=',1)->get();
        $barcode_template       =   barcode_template::where('deleted_at','=',NULL)->with('barcode_sheet')->get();
        $barcode_template_id    =   Auth::User()->barcode_template_id;

        $template_name_          =   Auth::User()['barcode_template']['template_name'];
        
        //
		$result = array();
		return view('barcodeprinting::barcodeprinting/barcode-printing',compact('result','barcode_sheet','barcode_template','barcode_template_id','template_name_'));
    }

    public function deleteTemplate(Request $request)
    {
        $barcode_template_id        =   $request->barcode_template_id;
        $userId                     =   Auth::User()->user_id;
        $created_by                 =   $userId;

        $result =  barcode_template::where('barcode_template_id', $barcode_template_id)
        ->update([
        'deleted_by' => $userId,
        'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return json_encode(array("Success"=>"True","Data"=>$result,"url"=>"barcode-printing"));
    }

    public function editTemplate(Request $request)
    {
        $barcode_template_id        =      $request->barcode_template_id;
        $userId                     =   Auth::User()->user_id;
        $created_by                 =   $userId;

        $result = barcode_template::select('*')
            ->where('barcode_template_id', '=', $barcode_template_id)
            ->where('deleted_at','=',NULL)
            ->where('created_by','=',$created_by)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
	
	public function bar_product_search(Request $request)
    {
		
        $result = product::select('product_name','product_system_barcode','product_id')
            ->where('product_name', 'LIKE', "%$request->search_val%")
            ->where('company_id',Auth::user()->company_id)->take(10)->get();
        

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
	
	public function barcode_search(Request $request)
    {
		
        $result = product::select('product_name','product_system_barcode','product_id')
            ->where('company_id',Auth::user()->company_id)
            ->Where('product_system_barcode', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function supplier_barcode_search(Request $request)
    {
        $result = product::select('supplier_barcode')
            ->where('company_id',Auth::user()->company_id)
            ->Where('supplier_barcode', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function category_search(Request $request)
    {
        $result = category::select('category_name')
            ->where('company_id',Auth::user()->company_id)
            ->Where('category_name', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function brand_search(Request $request)
    {
        $result = brand::select('brand_type')
            ->where('company_id',Auth::user()->company_id)
            ->Where('brand_type', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function size_search(Request $request)
    {
        $result = size::select('size_name')
            ->where('company_id',Auth::user()->company_id)
            ->Where('size_name', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function colour_search(Request $request)
    {
        $result = colour::select('colour_name')
            ->where('company_id',Auth::user()->company_id)
            ->Where('colour_name', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function sku_search(Request $request)
    {
        $result = product::select('sku_code')
            ->where('company_id',Auth::user()->company_id)
            ->Where('sku_code', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
	
	public function product_code(Request $request)
    {
		
        $result = product::select('product_code')
            ->where('company_id',Auth::user()->company_id)
            ->Where('product_code', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }
	
	public function invoice_no(Request $request)
    {
		
        $result = inward_stock::select('invoice_no')
            ->where('company_id',Auth::user()->company_id)
            ->Where('invoice_no', 'LIKE', "%$request->search_val%")->take(10)->get();

        return json_encode(array("Success"=>"True","Data"=>$result) );
    }

    public function BarcodePrintingSticker()
    {
        return view('barcodeprinting::barcodeprinting/barcode-sticker');
    }
	
    public function fetchBarcodeLabels(Request $request)
    {
        $data            =      $request->MasterType;
            
        $BarcodeData = barcode_sheet::select('barcode_sheet_id','label_name','label_tagline')
        ->where('barcode_id', '=', $data)
        ->where('is_active', '=', '1')
        ->get();

        return json_encode(array("Success"=>"True","Data"=>$BarcodeData) );
       
    }	

    public function template_save(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $created_by = $userId;

        // $template = barcode_template::updateOrCreate(

        $template = barcode_template::updateOrCreate(
            ['barcode_template_id' => '', 'barcode_sheet_id' => $data[0]['PrintBarcodeSheets'],'company_id'=>Auth::user()->company_id,],
            ['template_name'=>$data[0]['template_name'],
            'barcode_type'=>$data[0]['PrintBarcodeType'],
            'template_data'=>$data[0]['template_data'],
            'template_label_width'=>$data[0]['label_width'],
            'template_label_height'=>$data[0]['label_height'],
            'template_label_font_size'=>$data[0]['label_font_size'],
            'template_label_margin_top'=>$data[0]['label_margin_top'],
            'template_label_margin_right'=>$data[0]['label_margin_right'],
            'template_label_margin_bottom'=>$data[0]['label_margin_bottom'],
            'template_label_margin_left'=>$data[0]['label_margin_left'],
            'template_label_size_type'=>$data[0]['label_size_type'],
            'is_active'=>'1',
            'created_by' =>$created_by
            ]
        );

        return json_encode(array("Success"=>"True","Message"=>"Template successfully generated.","url"=>"barcode-printing"));

    }

    public function edit_template_save(Request $request)
    {
        $data = $request->all();

        $userId = Auth::User()->user_id;
        $created_by = $userId;

        $template = barcode_template::updateOrCreate(
            ['barcode_template_id' => $data[0]['barcode_template_id'],],
            ['company_id' => Auth::user()->company_id,
            'barcode_sheet_id' => $data[0]['PrintBarcodeSheets'],
            'template_name'=>$data[0]['template_name'],
            'barcode_type'=>$data[0]['PrintBarcodeType'],
            'template_data'=>$data[0]['template_data'],
            'template_label_width'=>$data[0]['label_width'],
            'template_label_height'=>$data[0]['label_height'],
            'template_label_font_size'=>$data[0]['label_font_size'],
            'template_label_margin_top'=>$data[0]['label_margin_top'],
            'template_label_margin_right'=>$data[0]['label_margin_right'],
            'template_label_margin_bottom'=>$data[0]['label_margin_bottom'],
            'template_label_margin_left'=>$data[0]['label_margin_left'],
            'template_label_size_type'=>$data[0]['label_size_type'],
            'is_active'=>'1',
            'created_by' =>$created_by
            ]
        );

        return json_encode(array("Success"=>"True","Message"=>"Template updated successfully.","url"=>"barcode-printing"));

    }

    public function saveBarcodeTemplateToUser(Request $request)
    {
        $data               =   $request->barcode_template_id;
        $userId             =   Auth::User()->user_id;
        $created_by         =   $userId;

        user::where('user_id',$created_by)->update(array(
            'barcode_template_id' => $data
        ));   

        return json_encode(array("Success"=>"True","Data"=>$data,"url"=>"barcode-printing"));

    }

    public function allBarcodeTemplates(Request $request)
    {

    }

	public function searchBarcodePrintProduct(Request $request)
    {
        if($request->ajax())
        {
            
            $data                   =      $request->all();
            $from_date              =      date("Y-m-d",strtotime($data['from_date']));
            $to_date	            =      date("Y-m-d",strtotime($data['to_date']));
            $productName            =      $data['productName'];
            $fBarcode               =      $data['fBarcode'];
            $tBarcode               =      $data['tBarcode'];
            $productCode            =      $data['productCode'];
			$invoiceNo              =      $data['invoiceNo'];

            $supplier_barcode       =   $data['supplier_barcode'];
            $category               =   $data['category'];
            // $subcategory            =   $data['subcategory'];
            $brandname              =   $data['brandname'];
            $sizename               =   $data['sizename'];
            $colourname             =   $data['colourname'];
            $skucode                =   $data['skucode'];


            if($productName!='')
            {
                $product_id = product::select('product_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('product_name', 'LIKE', "%$productName%")
                ->get();
            }

             if($fBarcode!='' and $tBarcode!='')
             {
                $product_id_barcode = product::select('product_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('product_system_barcode','>=',$fBarcode)
                 ->where('product_system_barcode','<=',$tBarcode)
                 ->get();
             }

             if($supplier_barcode!='')
             {
                $product_id_supplier_barcode = product::select('product_id')
                 ->where('company_id',Auth::user()->company_id)
                 ->where('deleted_at','=',NULL)
                 ->where('supplier_barcode','=',$supplier_barcode)
                 ->get();
             }

             if($productCode!='')
             {
                $product_code_id = product::select('product_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('product_code',$productCode)
                ->get();
             }

             if($skucode!='')
             {
                $product_sku_code_id = product::select('product_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('sku_code',$skucode)
                ->get();
             }

             if($invoiceNo!='')
             {
                $inward_id = inward_stock::select('inward_stock_id')
                ->where('company_id',Auth::user()->company_id)
                ->where('deleted_at','=',NULL)
                ->where('invoice_no',$invoiceNo)
                ->get();
             }

             if($category!='')
             {
                $category_id = category::select('category_id')
                ->whereRaw("categories.company_id='".Auth::user()->company_id."'")
                ->where('deleted_at','=',NULL)
                ->whereRaw("categories.category_name LIKE '%".$category."%'")
                ->get();
             }

             if($brandname!='')
             {
                $brand_id = brand::select('brand_id')
                ->whereRaw("brands.company_id='".Auth::user()->company_id."'")
                ->where('deleted_at','=',NULL)
                ->whereRaw("brands.brand_type LIKE '%".$brandname."%'")
                ->get();
             }

             if($sizename!='')
             {
                $size_id = size::select('size_id')
                ->whereRaw("sizes.company_id='".Auth::user()->company_id."'")
                ->where('deleted_at','=',NULL)
                ->whereRaw("sizes.size_name='".$sizename."'")
                ->get();

                // print_r($size_id);
             }

             if($colourname!='')
             {
                $colour_id = colour::select('colour_id')
                ->whereRaw("colours.company_id='".Auth::user()->company_id."'")
                ->where('deleted_at','=',NULL)
                ->whereRaw("colours.colour_name LIKE '%".$colourname."%'")
                ->get();

                // dd($colour_id);
             }
            
            $query = inward_product_detail::select('*')
            ->whereRaw("company_id='".Auth::user()->company_id."'")
            ->where('deleted_at','=',NULL)
            ->with('product');

            

            if($data['from_date']!='' and $data['to_date']!='')
            {
                $query->whereRaw("Date(inward_product_details.created_at) between '$from_date' and '$to_date'");
            }

            if($productName!='')
            {
                $query->whereIn('product_id',$product_id);
            }

            if($fBarcode!='' and $tBarcode!='')
            {
                $query->whereIn('product_id',$product_id_barcode);
            }

            if($supplier_barcode!='')
            {
                $query->whereIn('product_id',$product_id_supplier_barcode);
            }

            if($productCode!='')
            {
                $query->whereIn('product_id',$product_code_id);
            }

            if($skucode!='')
            {
                $query->whereIn('product_id',$product_sku_code_id);
            }

            if($category!='')
            {
                $pcategoryid   =  product::select('product_id')->whereIn('category_id',$category_id)->where('deleted_at','=',NULL)->get();
                $query->whereIn('product_id',$pcategoryid);
            }

            if($brandname!='')
            {
                $pbrandid   =  product::select('product_id')->whereIn('brand_id',$brand_id)->where('deleted_at','=',NULL)->get();
                $query->whereIn('product_id',$pbrandid);
            }

            if($sizename!='')
            {
                $psizeid   =  product::select('product_id')->whereIn('size_id',$size_id)->where('deleted_at','=',NULL)->get();
                $query->whereIn('product_id',$psizeid);
            }

            if($colourname!='')
            {
                $pcolorid   =  product::select('product_id')->whereIn('colour_id',$colour_id)->where('deleted_at','=',NULL)->get();
                $query->whereIn('product_id',$pcolorid);
            }

            if($invoiceNo!='')
            {
                $query->whereIn('inward_stock_id',$inward_id);
            }
            
             $query1 = inward_product_detail::select("inward_product_details.*",DB::raw("count(inward_product_detail_id) as totalCount"))
            ->whereRaw("company_id='".Auth::user()->company_id."'")
            ->where('deleted_at','=',NULL)
            ->with('product');

            if($data['from_date']!='' and $data['to_date']!='')
            {
                $query1->whereRaw("Date(inward_product_details.created_at) between '$from_date' and '$to_date'");
            }

            if($productName!='')
            {
                $query1->whereIn('product_id',$product_id);
            }

            if($fBarcode!='' and $tBarcode!='')
            {
                $query1->whereIn('product_id',$product_id_barcode);
            }

            if($supplier_barcode!='')
            {
                $query1->whereIn('product_id',$product_id_supplier_barcode);
            }

            if($productCode!='')
            {
                $query1->whereIn('product_id',$product_code_id);
            }

            if($skucode!='')
            {
                $query1->whereIn('product_id',$product_sku_code_id);
            }

            if($invoiceNo!='')
            {
                $query1->whereIn('inward_stock_id',$inward_id);
            }

            if($category!='')
            {
                $pcategoryid   =  product::select('product_id')->whereIn('category_id',$category_id)->where('deleted_at','=',NULL)->get();
                $query1->whereIn('product_id',$pcategoryid);
            }

            if($brandname!='')
            {
                $pbrandid   =  product::select('product_id')->whereIn('brand_id',$brand_id)->where('deleted_at','=',NULL)->get();
                $query1->whereIn('product_id',$pbrandid);
            }

            if($sizename!='')
            {
                $psizeid   =  product::select('product_id')->whereIn('size_id',$size_id)->where('deleted_at','=',NULL)->get();
                $query1->whereIn('product_id',$psizeid);
            }

            if($colourname!='')
            {
                $pcolorid   =  product::select('product_id')->whereIn('colour_id',$colour_id)->where('deleted_at','=',NULL)->get();
                $query1->whereIn('product_id',$pcolorid);
            }

            if($invoiceNo!='')
            {
                $query1->whereIn('inward_stock_id',$inward_id);
            }
            
            $result         =   $query->get(); 
            $result1        =   $query1->get();

            // $chk    =   $query->toSql();
            // dd($chk);
            // echo '<pre>';
            // print_r($result1); exit;
            // echo '</pre>';
            return view('barcodeprinting::barcodeprinting/view_printing_data',compact('result','result1'))->render();
        }
            
                
    }


    public function barcode_product_detail(Request $request)
    {
      
        $product_id   =  $request->product_id;
        $inward_id    =   $request->inward_id;
     
        $result = inward_product_detail::select('product_id','offer_price','product_mrp')
        ->Where('inward_product_detail_id','=',$inward_id)
        ->where('company_id',Auth::user()->company_id)
        ->with('product.colour','product.size','product.subcategory','product.category','product.brand')
        ->get();

        // $result = product::select('*')
        // ->Where('product_id','=',$product_id)
        // ->where('company_id',Auth::user()->company_id)
        // ->with('colour')
        // ->with('size')
        // ->with('price_master')
        // ->with('inward_product_detail')
        // ->with('subcategory')
        // ->with('category')
        // ->with('brand')
        // ->get();

        // $resultx = inward_product_detail::select('offer_price','product_mrp')
        // ->Where('inward_product_detail_id','=',$inward_id)
        // ->where('company_id',Auth::user()->company_id)
        // ->get();

        $userId             =   Auth::User()->user_id;
        $created_by         =   $userId;

        $company_id       =   Auth::user()->company_id;

        $company_name = company_profile::select('company_name')
        ->Where('company_id','=',$company_id)
        ->get();

        $CompanyName  =   $company_name[0]['company_name'];

        return json_encode(array("Success"=>"True","Data"=>$result,"CompanyName"=>$CompanyName));
    }

    public function fetchTemplateData(Request $request)
    {
        $barcode_template_id        =   $request->BarcodeTemplateId;

        $result1 = barcode_template::select('*')
        ->Where('barcode_template_id','=',$barcode_template_id)
        ->Where('is_active','=',1)
        ->with('barcode_sheet')
        ->get();
        
        return json_encode(array("Success"=>"True","Data1"=>$result1));
    }

    public function GenerateBarcode(Request $request)
    {
        $product_barcode    =   $request->product_barcode;
        $barcode_type       =   $request->barcode_type;
        
        $barcode    =   DNS1D::getBarcodePNG($product_barcode, $barcode_type,2,40, true);
        return json_encode(array("Data"=>$barcode));

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
     * @param  \App\BarcodePrinting  $barcodePrinting
     * @return \Illuminate\Http\Response
     */
    public function show(BarcodePrinting $barcodePrinting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BarcodePrinting  $barcodePrinting
     * @return \Illuminate\Http\Response
     */
    public function edit(BarcodePrinting $barcodePrinting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BarcodePrinting  $barcodePrinting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BarcodePrinting $barcodePrinting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BarcodePrinting  $barcodePrinting
     * @return \Illuminate\Http\Response
     */
    public function destroy(BarcodePrinting $barcodePrinting)
    {
        //
    }
}
