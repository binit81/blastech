<html>
<head>
    <title>
     Back Up
    </title>
    <style type="text/css">
.display-4{
    font-size:1.5rem !important;
}
.table thead tr.header th {
   
    font-size: 0.95rem !important;
}
.table tbody tr td {
   
    font-size: 0.92rem !important;
}

</style>
@extends('master')

@section('main-hk-pg-wrapper')
<div class="container ml-10">
<div class="row">
    <div class="col-xl-12">
<section class="hk-sec-wrapper" style="padding: 0.8rem 1.5rem 0 1.5rem !important;margin-top:5px !important;margin-bottom:5px !important;">
        <center><h4 class="hk-sec-title"><b>Back Up Manager</b></h4></center>
       
            

                
    </section>


   <div class="card">
            <div class="card-body pr-0 pl-0">
                <div class="row ma-0">
                    <div class="col-sm-12 pa-0">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                   <table class="table tablesaw view-bill-screen table-hover display pb-30 dataTable dtr-inline tablesaw-swipe"  role="grid" aria-describedby="datable_1_info" style="min-width:100% !important;" border="0">

                                    <div class="row" >
                                                <div class="col-md-9">
                                                    
                                                </div>

                                                <div class="col-md-3">
                                                     <a id="create-new-backup-button" href="{{URL::to('/create')}}" class="btn btn-primary pull-right" style="margin-bottom:2em;"><i class="fa fa-plus"></i> Create New Backup</a>
                                                </div>
                                            </div>
                                             <thead>
                                                <tr class="header">
                                                <th width="10%" style="text-align:center !important;">Sr No.</th>
                                                <th width="35%" style="text-align:left !important;">File Name</th>
                                                <th width="15%" style="text-align:left !important;">Size</th>
                                                <th width="15%" style="text-align:left !important;">Date</th>
                                                <th width="20%" style="text-align:center !important;">Action</th>
                                                <th width="5%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                           @foreach($backups as $key=>$backup)
                                      <?php
                                      $sr  =  $key +1;
                                      ?>
                                        <tr>
                                            <td style="text-align:center;">{{ $sr }}</td>
                                            <td style="text-align:left;">{{ $backup['file_name'] }}</td>
                                            <td style="text-align:left;">{{ $backup['file_size'] }}</td>
                                            <td style="text-align:left;">{{ $backup['last_modified'] }}</td>
                                           
                                            <td style="text-align:right;">
                                                <a class="btn btn-xs btn-default"
                                                   href="{{ route('backupDownload',$backup['file_name']) }}"><i
                                                        class="fa fa-cloud-download"></i> Download</a>
                                                <a class="btn btn-xs btn-default" data-button-type="delete"
                                                   href="{{ route('backupDelete',$backup['file_name']) }}"><i class="fa fa-trash"></i>
                                                    Delete</a>
                                            </td>
                                            <td></td>
                                        </tr>
@endforeach
                                            </tbody>
                                        </table>
                                       
                            </div>
                        </div><!--table-wrap-->
                    </div>
                </div>
            </div><!--card-body-->
        </div>
    </div>
  </div>
</div>
@endsection
