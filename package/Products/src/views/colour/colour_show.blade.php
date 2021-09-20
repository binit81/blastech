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
      <title>Size</title>
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
    <form id="colourform" >
        <input type="hidden" name="colour_id" value="" id="colour_id">

        <div class="col-md-12">
            <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
            <div class="col-md-12 topformbutton" style="margin-left: 30px">
                <button type="submit" class="btn-primary" name="addcolour" id="addcolour" >Add Colour</button>
                <span id="colourformerr" style="color: red"></span>
            </div>

            <div class="form-group col-md-12">
                <label for="colour_name"><b>Colour Name</b></label>
                <input class="form-control" placeholder="Colour Name" type="text" name="colour_name" id="colour_name" value="" maxlength="100">
            </div>

            <div class="form-group col-md-12">
                <label class="radio-inline"><input type="radio" name="colour_status" id="active" value="1" checked>Active</label>
                <label class="radio-inline"><input type="radio" name="colour_status" id="inactive" value="0">Inactive</label>
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
                      <th>Colour Name</th>
                      <th>status</th>
                      <th>Edit</th>
                      <th>Delete</th>
                  </tr>
                  </thead>
                  <tbody id="companyamc" class="cmpamc">
                  @foreach($colour as $key=>$colour)
                      <tr>
                          <td>{{$colour->colour_name}}</td>
                          <td>
                              @if($colour->is_active == 1)
                                  <a href="javascript:void(0)"><i class="fa fa-check" style="color:green"></i></a>
                                  @else
                                  <a href="javascript:void(0)"><i class="fa fa-close" style="color:red"></i></a>
                                  @endif
                          </td>
                          <td><a class="colour_edit" data-colour_id="{{encrypt($colour->colour_id)}}"><i class="fa fa-edit"></i></a></td>
                          <td><a class="colour_delete" data-colour_id="{{encrypt($colour->colour_id)}}"><i class="fa fa-trash"></i></a></td>
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

      jQuery(document).on('submit','#colourform',function(e){

          e.preventDefault();
          /*if(validate_brandform('brandform'))
          {*/
          var colour_name = $("#colour_name").val();
          var is_active = $('input[name=colour_status]:checked').val();
          var csrf_token = $('#csrf_token').val();
          var colour_id = $('#colour_id').val();
          $.post("{{ route('colour_create') }}",
              {_token:csrf_token,colour_id:colour_id,colour_name:colour_name,is_active:is_active},function(data)
              {
                  if (data['error'] == true) {
                      alert(data['message']);
                  } else {
                      $('#colourform').trigger('reset');
                      alert(data);
                  }
              });
/*

*/

      });


      jQuery(document).on('click','.colour_edit',function(e){

          e.preventDefault();

          var colour_id = $(this).data('colour_id');

          $.get("{{ route('colour_edit')}}",{ colour_id:colour_id },function(data)
          {
              $('#colour_id').val(data.colour_id);
              $('#colour_name').val(data.colour_name);
          });
      });

      jQuery(document).on('click','.colour_delete',function(e){
          e.preventDefault();
          var colour_id = $(this).data('colour_id');
          if(confirm('Are you sure you want delete this colour?')) {
              $.get("{{ route('colour_delete') }}",{ colour_id:colour_id}, function(data){
                  alert(data);
                  $('#msg').empty().append(data);


              });}else{
              return false;
          }
      });


  </script>

    @endsection

