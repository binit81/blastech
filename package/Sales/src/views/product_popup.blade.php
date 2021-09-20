<?php
if(sizeof($ppvalues)!=0)
{
  $sizename    = '--------';
  $colourname  = '--------';
  $subcatname  = '--------';
  $catname     = '--------';
  $uqcname     = '--------';
  $brandname   = '--------';
  $skucode     = '--------';
  $hsncode     = '--------';

  if($ppvalues[0]['size_id']!='' || $ppvalues[0]['size_id']!=null)
  {
     $sizename  =  $ppvalues[0]['size']['size_name'];
  }
  if($ppvalues[0]['colour_id']!='' || $ppvalues[0]['colour_id']!=null)
  {
     $colourname  =  $ppvalues[0]['colour']['colour_name'];
  }
  if($ppvalues[0]['subcategory_id']!='' || $ppvalues[0]['subcategory_id']!=null)
  {
     $subcatname  =  $ppvalues[0]['subcategory']['subcategory_name'];
  }
  if($ppvalues[0]['category_id']!='' || $ppvalues[0]['category_id']!=null)
  {
     $catname  =  $ppvalues[0]['category']['category_name'];
  }
  if($ppvalues[0]['uqc_id']!='' || $ppvalues[0]['uqc_id']!=null)
  {
     $uqcname  =  $ppvalues[0]['uqc']['uqc_name'];
  }
  if($ppvalues[0]['brand_id']!='' || $ppvalues[0]['brand_id']!=null)
  {
     $brandname  =  $ppvalues[0]['brand']['brand_name'];
  }
  if($ppvalues[0]['sku_code']!='' || $ppvalues[0]['sku_code']!=null)
  {
     $skucode  =  $ppvalues[0]['sku_code'];
  }
  if($ppvalues[0]['hsn_sac_code']!='' || $ppvalues[0]['hsn_sac_code']!=null)
  {
     $hsncode  =  $ppvalues[0]['hsn_sac_code'];
  }

?>
<div class="row">
    <div class="col-sm-12">
        <div class="hk-row">
          <div class="col-md-8">
             <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Product Name</label>
                    </div>
                    <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$ppvalues[0]['product_name']}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">SKU</label>
                    </div>
                     <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$skucode}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">HSN</label>
                    </div>
                     <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$hsncode}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Brand</label>
                    </div>
                     <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$brandname}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Category</label>
                    </div>
                     <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$catname}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Sub Category</label>
                    </div>
                     <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$subcatname}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Color</label>
                    </div>
                    <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$colourname}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">Size</label>
                    </div>
                    <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$sizename}}</label>
                    </div>
              </div>
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;">UQC</label>
                    </div>
                    <div class="col-sm-1">:
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;">{{$uqcname}}</label>
                    </div>
              </div>
             
              <div class="row">
                    <div class="col-sm-4">
                       <label style="text-align:left !important;color:#000;"></label>
                    </div>
                    <div class="col-sm-1">
                    </div>
                     <div class="col-sm-7">
                       <label style="text-align:left !important;color:#000;font-size:16px;"></label>
                    </div>
              </div>
          </div>
          <div class="col-md-4">
            <?php
                if(sizeof($ppvalues[0]['product_image'])!=0)
                {
                      
                }
                else
                {
            ?>
              <img src="dist/img/img-thumb.jpg" class="img-fluid img-thumbnail" alt="img">
            <?php
             }
          ?>
          </div>           
      </div>
  </div>


</div>
<?php
}
?>