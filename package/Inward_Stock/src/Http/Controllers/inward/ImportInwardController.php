<?php

namespace Retailcore\Inward_Stock\Http\Controllers\inward;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;



use Retailcore\Inward_Stock\Models\inward\import_inward;
use Illuminate\Http\Request;

class ImportInwardController extends Controller
{

    public function index()
    {
        //
    }



   public function import_inward(Request $request)
   {

       $data = Excel::load($request['file'])->get();


       print_r($data);
       exit;

           exit;
       $request->validate([
           'import_file' => 'required'
       ]);

       $path = $request->file('import_file')->getRealPath();
       $data = Excel::load($path)->get();

       $data = $request->all();
       $import_file = $data['import_file'];

       $request->validate([
           'import_file' => 'required'
       ]);

       $path = $request->file('import_file')->getRealPath();

       print_r($path);
       exit;

   }
}
