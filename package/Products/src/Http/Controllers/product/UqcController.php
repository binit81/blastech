<?php

namespace Retailcore\Products\Http\Controllers\product;
use App\Http\Controllers\Controller;
use Retailcore\Products\Models\product\uqc;
use Illuminate\Http\Request;
use Auth;
class UqcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uqc = uqc::get();

        return view('uqc.uqc_show',compact('uqc'));
    }

    public function get_uqc()
    {
        $uqc= uqc::get();
        return response()->json(array("Success"=>"True","Data"=>$uqc));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uqc_create(Request $request)
    {
        $data = $request->all();
        $uqcdata =  array();
        parse_str($data['formdata'], $uqcdata);

        $uqcdata = preg_replace('/\s+/', ' ', $uqcdata);
        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;

        $uqc_id =$request->uqc_id;


        $created_by = $userId;
        $uqc = uqc::updateOrCreate(
            ['uqc_id' => $uqc_id,
            ],
            [
                'created_by' =>$created_by,
                'uqc_name' => $uqcdata['uqc_name'],
                'uqc_type' => $uqcdata['uqc_type'],
                'uqc_shortname' => $uqcdata['uqc_shortname'],
                'is_active' => '1',
            ]
        );

        if($uqc)
        {
            if ($request->uqc_id != null)
            {
                return json_encode(array("Success"=>"True","Message"=>"UQC has been successfully updated."));
            } else {

                return json_encode(array("Success"=>"True","Message"=>"UQC has been successfully added."));
            }

        }
        else
        {
            return json_encode(array("Success"=>"False","Message"=>"Something Went Wrong"));
        }
        return back()->withInput();
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
     * @param  \App\uqc  $uqc
     * @return \Illuminate\Http\Response
     */
    public function show(size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\size  $size
     * @return \Illuminate\Http\Response
     */
    public function uqc_edit(Request $request)
    {
        $uqc_id= decrypt($request->uqc_id);
        $uqcdata= uqc::where([['uqcs.uqc_id','=',$uqc_id]])
            ->select('uqcs.*')
            ->first();

        return $uqcdata;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\size  $size
     * @return \Illuminate\Http\Response
     */
    public function uqc_delete(Request $request)
    {
        //
    }
}
