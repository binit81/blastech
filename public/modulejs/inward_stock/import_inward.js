$("body").on("click", "#upload", function ()
{
    $("#upload").attr('disabled',true);

    $(".loaderContainer").show();

    //Reference the FileUpload element.
    var fileUpload = $("#fileUpload")[0];

    var ext = fileUpload.value.split('.').pop();

    //Validate whether File is valid Excel file.
    var validImageTypes = ['xls', 'xlsx'];

    if (validImageTypes.includes(ext))
    {
        var file_name  = $('#fileUpload')[0].files[0]['name'];

        var type_of_inward = $("#inward_type").val();

        var name_of_file = '';
        var inward_name = '';
        var file_length = '';

        if(type_of_inward == 1)
        {
            name_of_file = 'fmcg_inward_stock_template';
            inward_name = "FMCG";
            file_length = 26;
        }

        if(type_of_inward == 2)
        {
            name_of_file = 'garment_inward_stock_template';
            inward_name = "GARMENT";
            file_length = 29;

        }

        if(name_of_file != '' && file_name.substr(0,file_length) == name_of_file) {
            if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();

                //For Browsers other than IE.
                if (reader.readAsBinaryString) {
                    reader.onload = function (e) {
                        ProcessExcel(e.target.result);
                    };
                    reader.readAsBinaryString(fileUpload.files[0]);
                } else {
                    //For IE Browser.
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }
                        ProcessExcel(data);
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            } else {
                $("#upload").attr('disabled', false);
                $(".loaderContainer").hide();
                alert("This browser does not support HTML5.");
            }
        }else
        {
            $("#upload").attr('disabled', false);
            $(".loaderContainer").hide();
            alert("Please upload "+inward_name+" excel file");
            $("#fileUpload").val('');
        }
    } else {
        $("#upload").attr('disabled',false);
        $(".loaderContainer").hide();
        alert("Please upload a valid Excel file.");
    }
});
function ProcessExcel(data) {
    //Read the Excel File data.

    var result;

    var workbook = XLSX.read(data, {
        type: 'binary'
    });

    //Fetch the name of First Sheet.
    var firstSheet = workbook.SheetNames[0];

    //Read all rows from First Sheet into an JSON array.
   var excelRows = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheet],{ defval: ''});

   var final_arr = [];
    $.each(excelRows,function (key,value)
    {
        var final_data = {};
        $.each(value,function (arrkwy,arrval)
        {
          if (!arrkwy.match("^__EMPTY"))
          {
              final_data[arrkwy] = arrval;
          }
        });
        final_arr.push(final_data);
    });

    var error = 0;

    $.each(final_arr,function (validate_key,validate_value)
    {
        if(validate_value['Product name'] == '')
        {
            error = 1;
           toastr.error("Product name can not be empty!");
        }
        if(validate_value['Base price/cost rate'] == "" ||validate_value['Base price/cost rate'] == 0)
        {
            error = 1;
           toastr.error("Base price/cost rate can not be empty or 0!");
        }
        if(validate_value['Base price/cost Rate'] != "" && !$.isNumeric(validate_value['Base price/cost rate']))
        {
                error = 1;
                toastr.error("Base price/cost rate must be numeric!");
        }
        var cost_gst_label_validation = 'Cost gst %';
        var sell_gst_label_validation = 'Sell gst %';

        if(tax_type == 1)
        {
            cost_gst_label_validation = 'Cost '+tax_title+' %';
            sell_gst_label_validation = 'Sell '+tax_title+' %';
        }

        if(validate_value[cost_gst_label_validation] == "")
        {
            error = 1;
            toastr.error(""+cost_gst_label_validation +"" +" can not be empty!");
        }
        if(validate_value[cost_gst_label_validation] != "" && !$.isNumeric(validate_value[cost_gst_label_validation]))
        {
            error = 1;
            toastr.error(""+cost_gst_label_validation+"" +" must be numeric!");
        }
        if(validate_value['Extra charge'] != "" && !$.isNumeric(validate_value['Extra charge']))
        {
            error = 1;
            toastr.error("Extra charge must be numeric!");
        }
        if(validate_value['Profit %'] != "" && !$.isNumeric(validate_value['Profit %']))
        {
            error = 1;
            toastr.error("Profit % must be numeric!");
        }

        if(validate_value['Selling price'] != "" && !$.isNumeric(validate_value['Selling price']))
        {
            error = 1;
            toastr.error("Selling price  must be numeric!");
        }

        if(validate_value[sell_gst_label_validation] == "")
        {
            error = 1;
            toastr.error(""+sell_gst_label_validation +"" +" can not be empty!");
        }


        if(validate_value['Offer price'] == '' && validate_value['Profit %'] == '' && validate_value['Selling price'] == '')
        {
            error = 1;
            toastr.error("Enter Offer price or Profit % or Selling price!");
        }

        var inward_types = $("#inward_type").val();

        if(inward_types == 1) {


            if (validate_value['Discount percent'] != "" && !$.isNumeric(validate_value['Discount percent'])) {
                error = 1;
                toastr.error("Discount percent must be numeric!");
            }

            if (validate_value['Scheme percent'] != "" && !$.isNumeric(validate_value['Scheme percent'])) {
                error = 1;
                toastr.error("Scheme percent must be numeric!");
            }

            if (validate_value['Free qty'] != '' && !$.isNumeric(validate_value['Free qty']))
            {
                error = 1;
                toastr.error("Free qty must be numeric!");
            }

            //CHECKING MFG DATE,MONTH AND YEAR DIGIT AND PROPER DATE FORMAT VALIDATION
            if(validate_value['Mfg date(DD)'] != '' || validate_value['Mfg month(MM)'] != '' || validate_value['Mfg year(YYYY)'] != '')
            {
                if(separtate_date_format_validation(validate_value['Mfg date(DD)'],validate_value['Mfg month(MM)'],validate_value['Mfg year(YYYY)'],"mfg") == 0)
                {
                    error  = 1;
                }
            }
            //END OF CHECKING MFG DATE FORMAT VALIDATION


            //CHECKING EXPIRY DATE VALIDATION
            if(validate_value['Expiry date(DD)'] != '' || validate_value['Expiry month(MM)'] != '' || validate_value['Expiry year(YYYY)'] != '')
            {
                if(separtate_date_format_validation(validate_value['Expiry date(DD)'],validate_value['Expiry month(MM)'],validate_value['Expiry year(YYYY)'],"expiry") == 0)
                {
                    error  = 1;
                }
            }
            //END OF CHECK EXPIRY DATE VALIDATION
        }


        if(validate_value[sell_gst_label_validation] != '' && !$.isNumeric(validate_value[sell_gst_label_validation]))
        {
            error = 1;
            toastr.error(""+sell_gst_label_validation +"" + "must be numeric!");
        }

        if(validate_value['Offer price'] != '' && !$.isNumeric(validate_value['Offer price']))
        {
            error = 1;
            toastr.error("Offer price must be numeric!")
        }

        if(validate_value['Product mrp'] == '')
        {
            error = 1;
           toastr.error("Product MRP can not be empty!");
        }

        if(validate_value['Product mrp'] != '' && !$.isNumeric(validate_value['Product mrp']))
        {
            error = 1;
            toastr.error("Product mrp must be numeric!");
        }
        if(validate_value['Add qty'] == '')
        {
            error = 1;
           toastr.error("Qty can not be empty!");
        }

        if(validate_value['Add qty'] != '' && !$.isNumeric(validate_value['Add qty']))
        {
            error = 1;
            toastr.error("Qty must be numeric!")
        }

        if(validate_value['HSN'] != '' && !$.isNumeric(validate_value['HSN']))
        {
            error = 1;
            toastr.error("HSN Code must be numeric!")
        }
        if(validate_value['Days before product expiry'] != '' && !$.isNumeric(validate_value['Days before product expiry']))
        {
            error = 1;
            toastr.error("Days before product expiry must be numeric!")
        }

        if(validate_value['Alert product qty'] != '' && !$.isNumeric(validate_value['Alert product qty']))
        {
            error = 1;
            toastr.error("Alert product qty must be numeric!")
        }
        if(error == 1)
        {
            $("#upload").attr('disabled',false);
            return false;
        }
    });

    if(error == 0)
    {
        checkproduct(final_arr);
    }
    else
    {
        $(".loaderContainer").hide();
    }
}

function checkproduct(arr)
{
    var  url = "product_check";
    var type = "POST";
    var dataarr = JSON.stringify(arr);

    $("#rec").attr('data-json',dataarr);
    callroute(url,type,arr,function (data)
    {
        //$("#upload").attr('disabled',false);
        var responce = JSON.parse(data);

        if(responce['Success'] == "True")
        {
            var record = responce['Data'][0];
                $(".loaderContainer").hide();
            if(record != '' )
            {
                var exist = '';

                $.each(record, function (key, value)
                {
                    exist += key + ',';
                });
                var jsondata = [];
                var newexist = exist.substring(0,exist.length-1);
                 jsondata['exist'] = JSON.stringify(record);
                 jsondata['arr'] = JSON.stringify(arr);

                var message = "There are new "+newexist+" found in excel.are you sure want to add this new "+newexist+"." +
                    "<a' id='showmoredata' onclick='showmore(this)';  data-attr='"+jsondata['exist']+"' ><strong style='color: #123f'>Click Here for view Detail</strong></a> ";
                if(newexist  != '')
                {
                    swal({
                            title: "info",
                            text: message,
                            type: "warning",
                            html: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes!",
                            showCancelButton: true,
                            closeOnConfirm: true,
                            closeOnCancel: true
                        },
                        function(isConfirm)
                        {
                            if (isConfirm)
                            {
                                var recarr = $("#rec").data('json');
                                insertproduct(recarr);
                            }

                        });
                }
            }
            else
            {
                var recarr = $("#rec").data('json');
                insertproduct(recarr);
            }
        }
        else
        {
            $(".loaderContainer").hide();
            toastr.error(responce['Message']);
            $("#upload").attr('disabled',false);

        }
    });
}
function showmore(obj)
{
    var rec = $(obj).data('attr');

    var recarr = $("#rec").data('json');

    var swal_html = '<div class="row">' ;

    if(rec !== '')
    {
        $.each(rec,function (key,value)
        {
            swal_html += '<table style="border: 1px solid #000000;margin-right: 25px"><tr><th>'+key+'</th></tr>';
            swal_html += '';
            $.each(value,function (k,v) {
                swal_html += '<tr style="border-bottom: 1px solid #000000"><td style="width: 7px;">'+v+'</td></tr>';
            });
            swal_html += '</table>';
        })
    }
    swal_html += '</div>';
    swal({
            title: "Detail",
            text: swal_html,
            type: "info",
            html: true,
            showConfirmButton : true,
            showCancelButton: false,
        },
        function(isConfirm)
        {
            if (isConfirm)
            {
                insertproduct(recarr);

            }

        });

}

function insertproduct(arr)
{
    $(".loaderContainer").show();

    var  url = "product_check";
    var type = "POST";
    var product_type = $("#inward_type").val();

    var data = {
        'formdata' :arr,
        'confirm' :'1',
        'product_type' :product_type
    };
    callroute(url,type,data,function (data)
    {
        var response = JSON.parse(data);
        if(response['Success'] == "True")
        {
            toastr.success(response['Message']);

            $("#rec_product").attr('data-json',JSON.stringify(response['Data']['formdata']));

            showinward();

        }
        else
        {
            toastr.error(response['Message']);
        }
    });
}
function showinward()
{
    var rec_data= $("#rec_product").data('json');
    $("#upload").attr('disabled',true);
    $("#fileUpload").attr('disabled',true);
    $("#productsearch").val('');
    $(".odd").hide();
    var billing_type = $("#billing_type").val();
    var inwardtype = $("#inward_type").val();
    var border_css = '';
    if(inwardtype == 1) {
        if (billing_type == 3) {
            border_css = 'border:1px solid red';
        }
    }
    $.each(rec_data,function (key,value)
    {
        var url = "inward_product_detail";
        var type = "POST";

        var data = {
            'product_name' : value['Product name'],
            'supplier_barcode' : value['Barcode'],
            'product_id' : value['product_id'],
        };
        callroute(url,type,data,function (data)
        {
            var product_html = '';
            var response = JSON.parse(data);

            if(response['Success'] == "True")
            {
                var dta = response['Data'][0];
                if (dta != '')
                {
                    var hsncode = '';
                    var product_id = value['product_id'];

                    var barcode = '';
                    if (dta['supplier_barcode'] != '' && dta['supplier_barcode'] != null)
                    {
                        barcode = dta['supplier_barcode'];
                    } else {
                        barcode = dta['product_system_barcode'];
                    }
                    if (dta['hsn_sac_code'] != null || dta['hsn_sac_code'] != undefined) {
                        hsncode = dta['hsn_sac_code'];
                    }
                    if (value['Discount percent'] == '' || value['Discount percent'] == null)
                    {
                        value['Discount percent'] = 0;
                    }
                    if (value['Scheme percent'] == '' || value['Scheme percent'] == null)
                    {
                        value['Scheme percent'] = 0;
                    }

                    var cost_gst_label = 'Cost gst %';
                    var sell_gst_label = 'Sell gst %';
                    if(tax_type == 1)
                    {
                        cost_gst_label = 'Cost '+tax_title+' %';
                        sell_gst_label = 'Sell '+tax_title+' %';
                    }

                    if (value[cost_gst_label] == '' || value[cost_gst_label] == null)
                    {
                        value[cost_gst_label] = 0;
                    }
                    if (value['Batch no'] == '' || value['Batch no'] == null)
                    {
                        value['Batch no'] = '';
                    }
                    var mfg_date = '';
                    var exp_date = '';


                    if((value['Mfg date(DD)'] != '' && value['Mfg date(DD)'] != null) || (value['Mfg month(MM)'] != '' && value['Mfg month(MM)'] != null) || (value['Mfg year(YYYY)'] != '' && value['Mfg year(YYYY)'] != null))
                    {
                        mfg_date = value['Mfg date(DD)']+'-'+value['Mfg month(MM)']+'-'+value['Mfg year(YYYY)'];
                    }

                    if((value['Expiry date(DD)'] != '' && value['Expiry date(DD)'] != null) || (value['Expiry month(MM)'] != '' && value['Expiry month(MM)'] != null) || (value['Expiry year(YYYY)'] != '' && value['Expiry year(YYYY)'] != null))
                    {
                        exp_date = value['Expiry date(DD)']+'-'+value['Expiry month(MM)']+'-'+value['Expiry year(YYYY)'];
                    }


                    /*if(value['mfg date'] != '' && value['mfg date'] != null)
                    {
                        mfg_date = value['mfg date'].replace(/\//g,'-');
                    }

                    if(value['exp date'] != '' && value['exp date'] != null)
                    {
                        exp_date = value['exp date'].replace(/\//g,'-');
                    }*/
                    var free_qty = 0;

                    if(value['Free qty'] != '' && value['Free qty'] != null)
                    {
                        free_qty = value['Free qty'];
                    }
                    var profit_per = 0;
                    if(value['Profit %'] != '' && value['Profit %'] != null)
                    {
                        profit_per = value['Profit %'];
                    }

                    if (value['Selling price'] == '' || value['Selling price'] == null)
                    {
                        if(profit_per != 0)
                        {
                            //var profit_amt = (Number())
                        }
                        value['Selling price'] = 0;
                    }

                    var offer_price = 0;
                    if(value['Offer price'] != '' && value['Offer price'] != null)
                    {
                        offer_price = value['Offer price'];
                    }

                    var extra_charge = 0;
                    if(value['Extra charge'] != '' && value['Extra charge'] != null)
                    {
                        extra_charge = value['Extra charge'];
                    }

                    // var rowCount = $('#product_detail_record tr').length;
                    key++;

                    product_html += '<tr id="product_' + product_id + '"   data-id="' + key + '">' +
                        '<input type="hidden" name="inward_product_detail_id_' + product_id + '" id="inward_product_detail_id_' + product_id + '" value=" ">' +
                        '<td onclick="removerow(' + key + ');"><i class="fa fa-close"></i></td>' +
                        '<td>' + barcode + '</td>' +
                        '<td>' + dta['product_name'] + '</td>' +
                        '<td>' + hsncode + '</td>' +
                        '<td  class="editablearea garment_case_hide" contenteditable="true" style="color: black;'+border_css+'" id="batch_no_' + product_id + '">'+value['Batch no']+'</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" onkeyup="baseprice(this);"  id="base_price_' + product_id + '" >' + value['Base price/cost rate'] + '</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea garment_case_hide" contenteditable="true" style="color: black;" onkeyup="discountpercent(this);" id="base_discount_percent_' + product_id + '">' + value['Discount percent'] + '</td>' +
                        '<td class="garment_case_hide"  id="base_discount_amount_' + product_id + '">0</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea garment_case_hide" contenteditable="true" style="color: black;" onkeyup="schemepercent(this);"  id="scheme_discount_percent_' + product_id + '">' + value['Scheme percent'] + '</td>' +
                        '<td  class="garment_case_hide" id="scheme_discount_amount_' + product_id + '">0</td>' +
                        '<td class="garment_case_hide" readonly  id="free_discount_percent_' + product_id + '">0</td>' +
                        '<td class="garment_case_hide"  readonly id="free_discount_amount_' + product_id + '">0</td>' +
                        '<td  readonly  id="cost_rate_' + product_id + '">0</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" onkeyup="costgstpercent(this);" id="gst_percent_' + product_id + '">' + value[cost_gst_label] + '</td>' +
                        '<td  readonly id="gst_amount_' + product_id + '">0</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" onkeyup="extracharge(this);" id="extra_charge_'+product_id+'">'+extra_charge+'</td>' +
                        '<td  readonly id="profit_percent_'+ product_id + '">' + profit_per + '</td>' +
                        '<td  readonly id="profit_amount_' + product_id + '">0</td>' +
                        '<td  readonly id="sell_price_' + product_id + '">' + value['Selling price'] + '</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" onkeyup="sellinggstpercent(this);" id="selling_gst_percent_' + product_id + '">' + value[sell_gst_label] + '</td>' +
                        '<td  id="selling_gst_amount_' + product_id + '">0</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" onkeyup="offerprice(this);" id="offer_price_' + product_id + '">'+offer_price+'</td>' +
                        '<td onkeypress = "return testCharacter(event);" class="number editablearea" contenteditable="true" style="color: black;" id="product_mrp_' + product_id + '">' + value['Product mrp'] + '</td>' +
                        '<td onkeydown="return validateNumber(event);" class="number editablearea " contenteditable="true" style="color: black;" onkeyup="addproductqty(this);" id="product_qty_' + product_id + '">' + value['Add qty'] + '</td>' +
                        '<td onkeydown = "return validateNumber(event);" class="number editablearea garment_case_hide" contenteditable="true" style="color: black;" onkeyup="freeqty(this);" id="free_qty_' + product_id + '">' + free_qty + '</td>' +
                        '<td contenteditable="true" class="editablearea garment_case_hide"  style="color: black;" onclick="return getdatepicker(\'mfg_date_' + product_id + '\');" id="mfg_date_' + product_id + '">' +mfg_date+ '</td>' +
                        '<td contenteditable="true" class="editablearea garment_case_hide" style="color: black;" onclick="return getdatepicker(\'expiry_date_' + product_id + '\');" id="expiry_date_' + product_id + '">' +exp_date+ '</td>' +
                        '<td readonly id="total_cost_' + product_id + '"></td>' +
                        '</tr>';
                }
                $("#product_detail_record").append(product_html);

                if($("#inward_type").val() == 2)
                {
                    $(".garment_case_hide").hide();
                }
                else
                {
                    $(".garment_case_hide").show();
                }

                $("#pending_qty_return").hide();
                $("#po_pending_show").hide();

                $("#base_price_"+product_id).keyup();
                $("#selling_gst_percent_"+product_id).keyup();
            }
        });
    });
    $(".loaderContainer").hide();

}

