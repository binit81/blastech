<?php
/**
 * Created by PhpStorm.
 * User: Hemaxi
 * Date: 18/2/19
 * Time: 10:45 AM
 */
?>
@include('pagetitle')
@extends('master')

@section('container-fluid')

  <html>
  <head>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <title>Sub Category</title>
      <style>
          #pagination {
              width: 100%;
              text-align: center;
          }

          #pagination ul li {
              display: inline;
              margin-left: 10px;
          }


      </style>
  </head>
  <body>
  <div id="page-content-wrapper">
      <!-- Keep all page content within the page-content inset div! -->
      <div class="page-content inset">
          <div class="row">
    <form id="subcategoryform" >
        <input type="hidden" name="subcategory_id" value="" id="subcategory_id">

        <div class="col-md-12">
            <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
            <div class="col-md-12 topformbutton" style="margin-left: 30px">
                <button type="submit" class="btn-primary" name="addsubcategory" id="addsubcategory" >Add Subcategory</button>
                <span id="subcategoryformerr" style="color: red"></span>
            </div>

            <div class="form-group col-md-12">
                <label for="category_id"><b>Select Category</b></label>
                <select name="category_id" id="category_id">
                    <option value="0">Please Select</option>
                @foreach($category AS $categorykey=>$categoryvalue)
                    <option value="{{$categoryvalue->category_id}}">{{$categoryvalue->category_name}}</option>
                @endforeach
                </select>
                {{--<input class="form-control" placeholder="" type="text" name="category_id" id="category_id" value="" >--}}
            </div>

            <div class="form-group col-md-12">
                <label for="subcategory_name"><b>Subcategory Name</b></label>
                <input class="form-control" placeholder="Subcategory Name" type="text" name="subcategory_name" id="subcategory_name" value="" maxlength="100">
            </div>

            <div class="form-group col-md-12">
                <label class="radio-inline"><input type="radio" name="subcategory_status" id="active" value="1" checked>Active</label>
                <label class="radio-inline"><input type="radio" name="subcategory_status" id="inactive" value="0">Inactive</label>
            </div>
        </div>
    </form>
          </div>
      </div>
  </div>

  <div id="ajaxSection">
      <div class="col-md-12">
          <div class="box box-primary">
              <table id="tablerecord" class="record table">
                  <thead>
                  <tr>
                      <th>SubCategory Name</th>
                      <th>Category Name</th>
                      <th>status</th>
                      <th>Edit</th>
                      <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody id="companyamc" class="cmpamc">
                  @foreach($subcategory as $key=>$subcategory)
                      <tr>
                          <td>{{$subcategory->subcategory_name}}</td>
                          <td>{{$subcategory->category_id}}</td>
                          <td>
                              @if($subcategory->is_active == 1)
                                  <a href="javascript:void(0)"><i class="fa fa-check" style="color:green"></i></a>
                                  @else
                                  <a href="javascript:void(0)"><i class="fa fa-close" style="color:red"></i></a>
                                  @endif
                          </td>
                          <td><a class="subcategory_edit" data-subcategory_id="{{encrypt($subcategory->subcategory_id)}}"><i class="fa fa-edit"></i></a></td>
                          <td><a class="subcategory_delete" data-subcategory_id="{{encrypt($subcategory->subcategory_id)}}"><i class="fa fa-trash"></i></a></td>
                      </tr>
                  @endforeach
                  <!--<tr>
                       <td>Column 1: Row 1</td>
                       <td>Column 2: Row 1</td>
                       <td>Column 3: Row 1</td>
                       <td>Column 4: Row 1</td>
                   </tr>-->

                  </tbody>
              </table>
              <div id="pagination"></div>
              <ul class="uk-pagination"></ul>

          </div>
      </div>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.27.2/js/components/pagination.js"></script>
  <script type="text/javascript">
      var  rows = [];
      $('#tablerecord > tbody  > tr').each(function(i,row)
      {
          return rows.push(row);
      });

      jQuery.noConflict();
      if(rows.length > 10) {
          jQuery('#pagination').pagination({
              dataSource: rows,
              pageSize: 10,
              callback: function (data, pagination) {
                  jQuery('tbody').html(data);
              }
          })
      }

      jQuery(document).on('submit','#subcategoryform',function(e){

          e.preventDefault();
          /*if(validate_brandform('brandform'))
          {*/
          var category_id = $("#category_id").val();
          var subcategory_name = $("#subcategory_name").val();
          var is_active = $('input[name=subcategory_status]:checked').val();
          var csrf_token = $('#csrf_token').val();
          var subcategory_id = $('#subcategory_id').val();
          $.post("{{ route('subcategory_create') }}",
              {_token:csrf_token,subcategory_id:subcategory_id,category_id:category_id,subcategory_name:subcategory_name,is_active:is_active},function(data)
              {
                  if (data['error'] == true) {
                      alert(data['message']);
                  } else {
                      $('#subcategoryform').trigger('reset');
                      alert(data);
                  }
              });
/*

*/

      });


      jQuery(document).on('click','.subcategory_edit',function(e){

          e.preventDefault();

          var subcategory_id = $(this).data('subcategory_id');

          $.get("{{ route('subcategory_edit')}}",{ subcategory_id:subcategory_id },function(data)
          {
              $('#subcategory_id').val(data.subcategory_id);
              $('#category_id').val(data.category_id);
              $('#subcategory_name').val(data.subcategory_name);
          });
      });

      jQuery(document).on('click','.subcategory_delete',function(e){
          e.preventDefault();
          var subcategory_id = $(this).data('subcategory_id');
          if(confirm('Are you sure you want delete this subcategory?')) {
              $.get("{{ route('subcategory_delete') }}",{ subcategory_id:subcategory_id}, function(data){
                  alert(data);
                  $('#msg').empty().append(data);


              });}else{
              return false;
          }
      });


  </script>

    @endsection

