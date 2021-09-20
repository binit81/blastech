<?php

if(sizeof($product_data)!=0)
{

?>

<table width="100%" border="0" cellpadding="6" cellspacing="2">

<?php
$imagelink = '';
foreach($product_data[0]['product_images'] as $imagekey=>$imageval)
{
      $imagelink   =  $imageval['product_image'];
}
if($imagelink!='')
{
    $kit_picture    =   'public/uploads/products/'.$imagelink;
}
else
{
    $kit_picture    =   'dist/img/img-thumb.jpg';
}

 ?>             <tr>
                    <td width="20%" align="center" valign="top"><span id="productkit_pic"><img src="{{$kit_picture}}" class="img-fluid img-thumbnail" width="100%"  alt="img"></span></td>
                    <td width="40%" valign="top">
                        <div class="pb-5">Product Name: <span id="productkit_name" class="bold">{{$product_data[0]['product_name']}}</span></div>
                        <div class="pb-5">SKU: <span id="productkit_sku" class="bold">{{$product_data[0]['sku_code']}}</span></div>
                        <div class="pb-5">Product Code: <span id="productkit_code" class="bold"></span>{{$product_data[0]['product_code']}}</div>
                        <div class="pb-5">Product Description.: <span id="productkit_desc" class="bold">{{$product_data[0]['product_description']}}</span></div>
                        <div class="pb-5">HSN: <span id="productkit_hsn" class="bold">{{$product_data[0]['hsn_sac_code']}}</span></div>
                        <div class="pb-5">Brand: <span id="productkit_brand" class="bold">{{$product_data[0]['brand']['brand_name']}}</span></div>
                        <div class="pb-5">Category: <span id="productkit_category" class="bold">{{$product_data[0]['category']['category_name']}}</span></div>
                        <div class="pb-5">Sub Category: <span id="productkit_subcat" class="bold">{{$product_data[0]['subcategory']['subcategory_name']}}</span></div>
                        <div class="pb-5">Colour: <span id="productkit_colour" class="bold">{{$product_data[0]['colour']['colour_name']}}</span></div>
                    </td>
                    <td width="40%" align="left" valign="top">
                        <div class="pb-5"><div class="pb-5">UQC: <span id="productkit_uqc" class="bold">{{$product_data[0]['uqc']['uqc_name']}}</span></div>
                        <div class="pb-5">Kit Barcode: <span id="productkit_barcode" class="bold">{{$product_data[0]['supplier_barcode']}}</span></div>
                        <div class="pb-5">Kit Selling Price: <span id="productkit_sellprice" class="bold">{{$product_data[0]['selling_price']}}</span></div>
                        <div class="pb-5">MRP: <span id="productkit_mrp" class="bold">{{$product_data[0]['offer_price']}}</span></div>
                    </td>
                  </tr>
                  <tr>
                  </table>
                  <b>Items in Kit</b>
                    <table class="table tablesaw table-bordered table-hover table-striped mb-0" width="100%" cellpadding="6" border="0" frame="box">
                      
                      <tr>
                        <td bgcolor="#D6D6D6">Sr No.</td>
                        <td bgcolor="#D6D6D6">Item</td>
                        <td bgcolor="#D6D6D6">Barcode</td>
                        <td bgcolor="#D6D6D6">Size</td>
                        <td bgcolor="#D6D6D6">Colour</td>
                        <td bgcolor="#D6D6D6">UQC</td>
                        <td bgcolor="#D6D6D6">Qty</td>
                        </tr>
                        <tbody class="productskitdetail">
                      <?php
                      foreach($product_data[0]['combo_products_detail'] as $comboval=>$combodetail)
                      {
                        $sr  =  $comboval + 1;
                        if($combodetail['product']['supplier_barcode']!='' || $combodetail['product']['supplier_barcode']!=NULL)
                        {
                            $barcode  = $combodetail['product']['supplier_barcode'];
                        }
                        else
                        {
                           $barcode  = $combodetail['product']['product_system_barcode'];
                        }
                        ?>
                          <tr>
                            <td>{{$sr}}</td>
                            <td>{{$combodetail['product']['product_name']}}</td>
                            <td>{{$barcode}}</td>
                            <td>{{$combodetail['product']['size']['size_name']}}</td>
                            <td>{{$combodetail['product']['colour']['colour_name']}}</td>
                            <td>{{$combodetail['product']['uqc']['uqc_name']}}</td>
                            <td>{{$combodetail['qty']}}</td>
                          </tr>

                        <?php
                      }
                      ?>
                    </tbody>
                      
                    </table>
<?php
}
?>                    