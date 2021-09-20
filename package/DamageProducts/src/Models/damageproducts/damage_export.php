<?php

namespace Retailcore\DamageProducts\Models\damageproducts;


use Retailcore\Products\Models\product\product;
use Retailcore\Company_Profile\Models\company_profile\company_profile;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use DB;

class damage_export implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public $from_date = '';
    public $to_date = '';
    public $damage_no_search = '';
    public $DamageType='';

    public function __construct($from_date,$to_date,$damage_no_search,$DamageType) {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->damage_no_search = $damage_no_search;
        $this->DamageType=$DamageType;

        $inward_type_from_comp = company_profile::where('company_id',Auth::user()->company_id)->select('tax_type','tax_title','currency_title')->first();

        $this->tax_type = $inward_type_from_comp['tax_type'];
        $this->tax_title = $inward_type_from_comp['tax_title'];
        $this->currency_title = $inward_type_from_comp['currency_title'];

    }

    public function headings(): array
    {
        $tax_title = 'GST';
        if($this->tax_type == 1)
        {
            $tax_title = $this->tax_title;
        }
        $damage_header = [];

        $damage_header[] = 'Date';
        $damage_header[] = 'Damage Type';
        $damage_header[] = 'Damage No.';
        $damage_header[] = 'Total Damage Qty';
        $damage_header[] = 'Total Cost Rate';
        $damage_header[] = 'Total '.$tax_title. ' Amount';
        $damage_header[] = 'Total Cost Price';

        return $damage_header;

    }

    public function map($damage): array
   {
        $rows    = [];
        $rows[]         =   $damage->created_at->format('d-m-Y');
        $rows[]         =   $damage->damage_types['damage_type'];
        $rows[]         =   $damage->damage_no;
        $rows[]         =   $damage->damage_total_qty;
        $rows[]         =   $damage->damage_total_cost_rate;
        $rows[]         =   $damage->damage_total_gst;
        $rows[]         =   $damage->damage_total_cost_price;

        return $rows;
        
    }

    public function query()
    {
        $company_id     =   Auth::user()->company_id;
        $damage = damage_product::query()->whereRaw('company_id='.$company_id)->with('damage_types');

        if($this->from_date!='')
        {
            $damage->whereRaw("Date(damage_products.created_at) between '$this->from_date' and '$this->to_date'");
        }

        if($this->damage_no_search!='')
        {
            $damage->where('damage_no','=',$this->damage_no_search);
        }

        if($this->DamageType!='')
        {
            $damage->where('damage_type_id','=',$this->DamageType);
        }

        $damage->get();

        return $damage;
    }
}

