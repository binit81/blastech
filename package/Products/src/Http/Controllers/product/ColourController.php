<?php

namespace Retailcore\Products\Http\Controllers\product;
use App\Http\Controllers\Controller;

use Retailcore\Products\Models\product\colour;
use Illuminate\Http\Request;
use Auth;

class ColourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colour = colour::where('company_id', Auth::user()->company_id)->get();

        return view('colour.colour_show', compact('colour'));
    }

    public function get_colour()
    {
        $colour = colour::where('company_id', Auth::user()->company_id)->get();
        return response()->json(array("Success" => "True", "Data" => $colour));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function colour_create(Request $request)
    {

        $data = $request->all();
        $colourdata = array();
        parse_str($data['formdata'], $colourdata);
        $colourdata = preg_replace('/\s+/', ' ', $colourdata);
        $userId = Auth::User()->user_id;
        $company_id = Auth::User()->company_id;

        $colour_id = $request->colour_id;


        $created_by = $userId;
        $colour = colour::updateOrCreate(
            ['colour_id' => $colour_id, 'company_id' => $company_id,
            ],
            [
                'created_by' => $created_by,
                'company_id' => $company_id,
                'colour_name' => $colourdata['colour_name'],
                'is_active' => '1',
            ]
        );


        if ($colour) {
            if ($request->colour_id != null) {
                return json_encode(array("Success" => "True", "Message" => "Colour has been successfully updated."));
            } else {

                return json_encode(array("Success" => "True", "Message" => "Colour has been successfully added.","colour_id"=>$colour->colour_id));
            }
        } else {
            return json_encode(array("Success" => "False", "Message" => "Something Went Wrong"));
        }


        return back()->withInput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\colour $colour
     * @return \Illuminate\Http\Response
     */
    public function show(colour $colour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\colour $colour
     * @return \Illuminate\Http\Response
     */
    public function colour_edit(Request $request)
    {
        $colour_id = decrypt($request->colour_id);
        $colourdata = colour::where([['colours.colour_id', '=', $colour_id]])
            ->select('colours.*')
            ->first();

        return $colourdata;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\colour $colour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\colour $colour
     * @return \Illuminate\Http\Response
     */
    public function colour_delete(Request $request)
    {
        //
    }
}
