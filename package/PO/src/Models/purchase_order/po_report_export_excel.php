<?php

namespace Retailcore\PO\Models\purchase_order;

use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class po_report_export_excel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $po_no = '';
    public $supplier_name = '';

    public function __construct($from_date,$to_date,$po_no,$supplier_name)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->po_no = $po_no;
        $this->supplier_name = $supplier_name;
    }

    public function headings(): array
    {
        return [
            'Po No.',
            'Po Date',
            'Supplier Name',
            'Delivery Date',
            'Qty Required',
            'Qty Received',
            'Qty Pending',
        ];
    }

    public function map($po_export): array
    {
        $count = '';

        $rows    = [];


        $rows[] = $po_export->po_no;
        $rows[] = $po_export->po_date;
        $rows[] = $po_export->supplier_gstdetail->supplier_company_info->supplier_first_name .' '.$po_export->supplier_gstdetail->supplier_company_info->supplier_last_name;
        $rows[] = $po_export->delivery_date;
        $rows[] = $po_export->total_qty;
        $rows[] = $po_export->received_qty;
        $rows[] = $po_export->pending_qty;


        return $rows;
    }



    public function query()
    {
        $from_date   =   $this->from_date;
        $to_date   =   $this->to_date;
        $po_no   =   $this->po_no;
        $supplier_name =   $this->supplier_name;

        $po_export = purchase_order::where('company_id',Auth::user()->company_id)
            ->where('deleted_at','=',NULL);

       /* if(isset($po_export[0]) && $po_export[0] != '') {
            foreach ($po_export AS $key => $value) {

                $qty = purchase_order_detail::where('company_id', Auth::user()->company_id)
                    ->selectRaw('SUM(received_qty) AS received_qty,SUM(pending_qty) AS pending_qty')
                    ->where('received_qty', '!=', NULL)
                    ->where('purchase_order_id', '=', $value['purchase_order_id'])
                    ->groupBy('purchase_order_id')
                    ->first();

                $recieved_qty = isset($qty['received_qty']) && $qty['received_qty'] != '' ? $qty['received_qty'] : 0;

                $po_export[$key]['received_qty'] = $recieved_qty;
                $po_export[$key]['pending_qty'] = $qty['pending_qty'];

            }
        }*/

        if($from_date !='' && $to_date !='')
        {
            $po_export->purchase_order::where('po_date',[$from_date,$to_date]);
        }

        if($po_no !='')
        {
            $po_export->purchase_order::where('po_no', 'like', "%".$po_no."%");
        }

        if ($supplier_name != '')
        {
            $po_export->purchase_order::where('supplier_gst_id', '=', $supplier_name);
        }



       // $po_export = $po_export->orderBy('purchase_order_id', 'desc');
        return $po_export ;


    }
}
