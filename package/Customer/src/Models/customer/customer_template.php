<?php

namespace Retailcore\Customer\Models\Customer;

use App\country;
use Illuminate\Database\Eloquent\Model;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;

class customer_template implements WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Customer Name',
            'Gender',
            'Customer Mobile Country Code',
            'Mobile No.',
            'Email',
            'GSTIN',
            'Day of Birth(DD)',
            'Month of Birth(MM)',
            'Year of Birth(YYYY)',
            'Flat no.,Building,Street etc.',
            'Area',
            'City / Town',
            'Pin / Zip Code',
            'State / Region',
            'Country',
            'Credit Period(days)',
            'How did you came to know about us?',
            'Note',
        ];
    }

}
