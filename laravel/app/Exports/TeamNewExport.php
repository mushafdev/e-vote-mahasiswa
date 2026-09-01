<?php

namespace App\Exports;

use App\Models\Teams;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class TeamNewExport implements FromQuery, WithHeadings
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
        // return DB::table('teams')->whereRaw("status_verifikasi='N' AND no_anggota1 is NULL")->orderBy('status_verifikasi','ASC')->orderBy('created_at','DESC');
        return DB::table('teams')->selectRaw("created_at, nama, CONCAT(no_anggota1,no_anggota2) as no_anggota,alamat,kab_kota,telephone, email")->whereRaw("status_verifikasi='N' AND no_anggota1 is NULL")->orderBy('created_at','DESC');
    }

    public function headings(): array
    {
        return ["Waktu","Nama", "No. Anggota", "Alamat","Kab/Kota","No. Handphone","Email"];
    }
}
