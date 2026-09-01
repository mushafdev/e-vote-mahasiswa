<?php
namespace App\Helpers;
use File;
use DateTime;
use App\Models\Setting;
use App\Models\UserTheme;
use Illuminate\Support\Facades\DB;

class Formatter {
    public static function encrypt0($value) {
        $ciphering = "AES-256-CBC";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '6568765543568875';
        $encryption_key = "@#-MushafSijaya1033323sasaAsas2-+add##!!!";
        // $encryption_iv = '5498747891017261';
        // $encryption_key = "@#-MushafSijaya10!!!";
        $encryption = openssl_encrypt($value, $ciphering,$encryption_key, $options, $encryption_iv);
        return base64_encode($encryption);
    }

    public static function decrypt0($encryption) {
        $encryption=base64_decode($encryption);
        $ciphering = "AES-256-CBC";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '6568765543568875';
        $decryption_key = "@#-MushafSijaya1033323sasaAsas2-+add##!!!";
        $decryption = openssl_decrypt($encryption, $ciphering,$decryption_key, $options, $decryption_iv);
        return $decryption;
        }

        public static function jsEscape($str) {
            $output = '';
            $str = str_split($str);
            for($i=0;$i<count($str);$i++) {
                $chrNum = ord($str[$i]);
                $chr = $str[$i];
                if($chrNum === 226) {
                    if(isset($str[$i+1]) && ord($str[$i+1]) === 128) {
                        if(isset($str[$i+2]) && ord($str[$i+2]) === 168) {
                            $output .= '\u2028';
                            $i += 2;
                            continue;
                        }
                        if(isset($str[$i+2]) && ord($str[$i+2]) === 169) {
                            $output .= '\u2029';
                            $i += 2;
                            continue;
                        }
                    }
                }
                switch($chr) {
                    case "'":
                    case '"':
                    case "\n";
                    case "\r";
                    case "&";
                    case "\\";
                    case "<":
                    case ">":
                        $output .= sprintf("\\u%04x", $chrNum);
                    break;
                    default:
                        $output .= $str[$i];
                    break;
            }
            }
            return $output;
        }

    public static function description($str){
        $desc=substr(strip_tags(html_entity_decode($str)), 0, 100) . '...';
        return $desc;
    }

    public static function description_lm($str){
        $desc=substr(strip_tags(html_entity_decode($str)), 0, 300) . '...';
        return $desc;
    }
    public static function description_sm($str){
        $desc=substr(strip_tags(html_entity_decode($str)), 0, 100) . '...';
        return $desc;
    }

    public static function to_datetime($datetime){
        $result=date_format($datetime, 'd/m/Y H:i:s');
        return $result;
    }

    public static function getDateDatetime($datetime){
        if(!empty($datetime)){
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
            $result=$datetime->format('Y-m-d');
        }else{
            $result='-';
        }

        return $result;
    }

    public static function getTimeDatetime($datetime){
        if(!empty($datetime)){
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
            $result=$datetime->format('H:i');
        }else{
            $result='-';
        }

        return $result;
    }
    
    public static function getDateDatetimeShow($datetime){
        if(!empty($datetime)){
            $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
            $result=$datetime->format('d/m/Y');
        }else{
            $result='-';
        }

        return $result;
    }


    public static function datetime($waktu)
    {
        if(!empty($waktu)){
            $waktu = DateTime::createFromFormat('Y-m-d H:i:s', $waktu);
            $result=$waktu->format('d/m/Y H:i:s');
        }else{
            $result='-';
        }

        return $result;
    }

    public static function todate2($tgl)
    {
        $tgl = DateTime::createFromFormat('Y-m-d', $tgl);
        return $tgl->format('d/m/Y');
    }

    public static function to_hour($hour){
        if(!empty($hour)){
            $pecahkan = explode(':', $hour);
            return $pecahkan[0] . ':' . $pecahkan[ 1 ];

        }else{
            return '--:--';
        }

    }

    public static function toMonth($m)
    {
    $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
    );
    return $bulan[$m];
    }

    public static function toRp($angka)
    {
        $rp = number_format($angka,2,',','.');
        return $rp;
    }
    
    public static function pointSeparator($angka)
    {
        $rp = number_format($angka,0,',','.');
        return $rp;
    }

    public static function RptoDb($angka)
    {
    $db = str_replace('.', '', $angka);
    $db2 = str_replace(',', '.', $db);
    return $db2;
    }

    public static function identity()
    {
        $result = array (
                'title'=>   "E-Vote Mahasiswa",
                'singkat'=>   'E-Vote Mahasiswa',
                'sub_title'=>'Sistem Pemilihan Elektronik Berbasis Face Recognition',
                'text'=>'',
                'regency_id'=>0,
                'by'=>'E-Vote',
                'tgl_eks'=>'2024-11-27 08:00:00',
                'tot_dpt'=>109653,
                'support'=>'',
                'organization_name'=>'HIMATIK FT UNM ',
                'cloudflare'=>'none', //(active/none)
                'email_verif'=>false, //(true/false) false = skip verifikasi email, langsung aktif
        );
        return $result;
    }

    public static function setting()
    {
        $result = Setting::first();
        return $result;
    }
    
    public static function theme()
    {
        // $theme = UserTheme::where('user_id',auth()->user()->id)->get();
        $result=array(
            'header'=>'',
            'sidebar'=>'',
            //New Update
            'page' => 'page-light',
            //New Update
        );
        // foreach($theme as $t){
        //     $result[$t->type]=$t->class;
        // }
        return $result;
    }

    public static function deleteImageFile($main_folder,$file)
    {
        File::delete([
            public_path('storage/images/'.$main_folder.'/'.$file),
            public_path('storage/images/'.$main_folder.'_middle/'.$file),
            public_path('storage/images/'.$main_folder.'_small/'.$file)
        ]);
    }

    public static function deleteFile($main_folder,$file)
    {
        File::delete([
            public_path('storage/files/'.$main_folder.'/'.$file)
        ]);
    }

    public static function posisi_surat_label($posisi,$nm_bidang)
    {
        if(!empty($nm_bidang)){
            $label='<span class="badge badge-warning">Diteruskan ke '.$nm_bidang.'</span>';
        }else{
            if($posisi=='a'){
                $posisi='Admin';
            }
            $label='<span class="badge badge-warning">Diteruskan ke '.$posisi.'</span>';
        }
        return $label;
    }

    
    public static function kelompok_tim($id)
    {
        $result=DB::table('kelompok_tims')->where('id',$id)->first();
        return $result;
    }
 
    public static function level_prioritas_label($level)
    {
        if($level=='High'){
            $result='<span class="badge badge-danger">'.$level.'</span>';
        }elseif($level=='Normal'){
            $result='<span class="badge badge-warning">'.$level.'</span>';
        }else{
            $result='<span class="badge badge-info">'.$level.'</span>';
        }
        return $result;
    }

    public static function getdiff_minute_from_created_at($created_at)
    {
        $start_date = new DateTime($created_at);
        $since_start = $start_date->diff(new DateTime(date('Y-m-d H:i:s')));
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        return $minutes;
    }

    public static function insert_surat_masuk_timeline($surat_masuk_id,$aktor,$keterangan)
    {

        DB::table('surat_masuk_timelines')->insert([
            'surat_masuk_id' => $surat_masuk_id,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'aktor' => $aktor,
            'keterangan' => $keterangan
        ]);
        return response()->json([
            'text' => 'Input Success'
        ]);
    }

    public static function insert_surat_keluar_timeline($surat_keluar_id,$aktor,$keterangan)
    {

        DB::table('surat_keluar_timelines')->insert([
            'surat_keluar_id' => $surat_keluar_id,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'aktor' => $aktor,
            'keterangan' => $keterangan
        ]);
        return response()->json([
            'text' => 'Input Success'
        ]);
    }

    public static function tgl_indo($tanggal){
        if($tanggal=='' || $tanggal=='0000-00-00' ){
            return '-';
        }else{
            $bulan = array (
                1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
            $pecahkan = explode('-', $tanggal);
            return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
        }

    }

    public static function getDay($tanggal){
        $daftar_hari = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
           );
           $namahari = date('l', strtotime($tanggal));

           return $daftar_hari[$namahari];

    }

    public static function getMonthFromNumber($num){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        return $bulan[$num];

    }

    public static function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = Formatter::penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = Formatter::penyebut($nilai/10)." puluh". Formatter::penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . Formatter::penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = Formatter::penyebut($nilai/100) . " ratus" . Formatter::penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . Formatter::penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = Formatter::penyebut($nilai/1000) . " ribu" . Formatter::penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = Formatter::penyebut($nilai/1000000) . " juta" . Formatter::penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = Formatter::penyebut($nilai/1000000000) . " milyar" . Formatter::penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = Formatter::penyebut($nilai/1000000000000) . " trilyun" . Formatter::penyebut(fmod($nilai,1000000000000));
        }
        return $temp;
        }
    
        public static function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(Formatter::penyebut($nilai));
        } else {
            $hasil = trim(Formatter::penyebut($nilai));
        }
        return $hasil;
        }


    
}
