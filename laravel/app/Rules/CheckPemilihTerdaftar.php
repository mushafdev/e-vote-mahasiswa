<?php

namespace App\Rules;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class CheckPemilihTerdaftar implements Rule
{
    public $noAnggota;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($noAnggota)
    {
        $this->noAnggota = $noAnggota;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cek=DB::table('pemilihs')->where('no_anggota', $this->noAnggota)->count();
        return $cek==0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'NIM / No. Identitas ini sudah terdaftar';
    }
}
