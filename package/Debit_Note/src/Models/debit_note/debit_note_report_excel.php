<?php

namespace Retailcore\Debit_Note\Models\debit_note;


use Illuminate\Database\Eloquent\Model;


use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class debit_note_report_excel implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $debit_no = '';

    public function __construct($from_date,$to_date,$debit_no) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->debit_no = $debit_no;

    }


    public function headings(): array
    {
        $debit_note_product = [];
        $debit_note_product[] = 'Debit Receipt No.';
        $debit_note_product[] = 'Product Name';
        $debit_note_product[] = 'Supplier Name	';
        $debit_note_product[] = 'Return Qty';
        $debit_note_product[] = 'Debit Amt';
        $debit_note_product[] = 'Remarks';

        return $debit_note_product;
    }

    public function map($debit_note_result): array
    {

        $rows    = [];
        $rows[] = $debit_note_result->debit_note->debit_no;
        $rows[] = $debit_note_result->product->product_name;
        $rows[] = $debit_note_result->debit_note->supplier_gstdetail->supplier_company_info->supplier_first_name;
        $rows[] = $debit_note_result->return_qty;
        $rows[] = $debit_note_result->total_cost_price;
        $rows[] = $debit_note_result->remarks;
        return $rows;
    }



    public function query()
    {

        $from_date   =   $this->from_date;
        $to_date   =   $this->to_date;
        $debit_no =   $this->debit_no;

        $debit_note_result =  debit_product_detail::where('company_id',Auth::user()->company_id)
            ->whereNull('deleted_at')
            ->with('debit_note')
            ->with('product');

        if($from_date !='' && $to_date !='')
        {
            $debit_note_result->whereHas('debit_note',function ($q) use($from_date,$to_date)
            {
                $q->whereBetween('debit_date', [$from_date,$to_date]);
            });

        }
        if($debit_no !='')
        {
            $debit_note_result->whereHas('debit_note',function ($q) use($debit_no)
            {
                $q->whereRaw("debit_notes.debit_no='" . $debit_no . "'");
            });
        }

        return $debit_note_result;
    }

}
