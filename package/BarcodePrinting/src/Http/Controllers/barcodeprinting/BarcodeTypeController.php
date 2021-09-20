<?php

namespace App\Http\Controllers;

use Retailcore\BarcodePrinting\Models\barcodeprinting\barcode_type;
use Illuminate\Http\Request;

class BarcodeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //ALTER TABLE `users`
      ADD KEY `barcode_template_id` (`barcode_template_id`);

    ALTER TABLE `users`
      ADD CONSTRAINT `barcode_tempalte_id` FOREIGN KEY (`barcode_template_id`) REFERENCES `barcode_templates` (`barcode_template_id`)
    COMMIT;
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
     * @param  \App\barcode_type  $barcode_type
     * @return \Illuminate\Http\Response
     */
    public function show(barcode_type $barcode_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\barcode_type  $barcode_type
     * @return \Illuminate\Http\Response
     */
    public function edit(barcode_type $barcode_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\barcode_type  $barcode_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, barcode_type $barcode_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\barcode_type  $barcode_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(barcode_type $barcode_type)
    {
        //
    }
}
