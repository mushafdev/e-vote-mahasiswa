<?php

namespace App\Exports;

use App\Models\Teams;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class TeamExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // use Exportable;

    public function __construct()
    {

    }

    public function query()
    {
        return DB::table('teams')->selectRaw("nama, CONCAT(no_anggota1,no_anggota2) as no_anggota,alamat,telephone, email")->where('status_verifikasi','Y')->orderBy('created_at','DESC');
    }

    public function headings(): array
    {
        return ["Nama", "No. Anggota", "Alamat","No. Handphone","Email"];
    }
}
