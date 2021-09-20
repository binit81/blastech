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

@section('pcoded-inner-content')

  <html>
  <head>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <title>Category</title>
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
    <form id="categoryform" >
        <input type="hidden" name="category_id" value="" id="category_id">

        <div class="col-md-12">
            <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
            <div class="col-md-12 topformbutton" style="margin-left: 30px">
                <button type="submit" class="btn-primary" name="addcategory" id="addcategory" >Add Category</button>
                <span id="categoryformerr" style="color: red"></span>
            </div>

            <div class="form-group col-md-12">
                <label for="category_name"><b>Category Name</b></label>
                <input class="form-control" placeholder="Category Name" type="text" name="category_name" id="category_name" value="" maxlength="100">
            </div>

            <div class="form-group col-md-12">
                <label class="radio-inline"><input type="radio" name="category_status" id="active" value="1" checked>Active</label>
                <label class="radio-inline"><input type="radio" name="category_status" id="inactive" value="0">Inactive</label>
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
                      <th>Category Name</th>
                      <th>status</th>
                      <th>Edit</th>
                      <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody id="companyamc" class="cmpamc">
                  @foreach($category as $key=>$category)
                      <tr>
                          <td>{{$category->category_name}}</td>
                          <td>
                              @if($category->is_active == 1)
                                  <a href="javascript:void(0)"><i class="fa fa-check" style="color:green"></i></a>
                                  @else
                                  <a href="javascript:void(0)"><i class="fa fa-close" style="color:red"></i></a>
                                  @endif
                          </td>
                          <td><a class="category_edit" data-categories_id="{{encrypt($category->category_id)}}"><i class="fa fa-edit"></i></a></td>
                          <td><a class="category_delete" data-categories_id="{{encrypt($category->category_id)}}"><i class="fa fa-trash"></i></a></td>
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

      jQuery(document).on('submit','#categoryform',function(e){

          e.preventDefault();
          /*if(validate_categoryform('categoryform'))
          {*/
          var category_name = $("#category_name").val();
          var is_active = $('input[name=category_status]:checked').val();
          var csrf_token = $('#csrf_token').val();
          var category_id = $('#category_id').val();
          $.post("{{ route('category_create') }}",
              {_token:csrf_token,category_id:category_id,category_name:category_name,is_active:is_active},function(data)
              {
                  if (data['error'] == true) {
                      alert(data['message']);
                  } else {
                      $('#categoryform').trigger('reset');
                      alert(data);
                  }
              });
   });


      jQuery(document).on('click','.category_edit',function(e){

          e.preventDefault();

          var category_id = $(this).data('categories_id');

          $.get("{{ route('category_edit')}}",{ category_id:category_id },function(data)
          {
              $('#category_id').val(data.category_id);
              $('#category_name').val(data.category_name);
          });
      });

      jQuery(document).on('click','.category_delete',function(e){
          e.preventDefault();
          var category_id = $(this).data('categories_id');
          if(confirm('Are you sure you want delete this category?')) {
              $.get("{{ route('category_delete') }}",{ category_id:category_id}, function(data){
                  alert(data);
                  $('#msg').empty().append(data);

                  categories();
              });}else{
              return false;
          }
      });


  </script>

    @endsection

