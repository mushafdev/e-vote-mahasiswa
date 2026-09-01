<?php

namespace App\Rules;
use Closure;
use App\Models\BarangStok;
use Illuminate\Contracts\Validation\Rule;

class CheckStock implements Rule
{
    public $barangId;
    public $unitKerjaId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($barangId,$unitKerjaId)
    {
        $this->barangId = $barangId;
        $this->unitKerjaId = $unitKerjaId;
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
        $stok=BarangStok::whereRaw("barang_id='$this->barangId' AND unit_kerja_id='$this->unitKerjaId'")->sum('stok');
        return $stok>=$value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Stok tidak cukup';
    }
}
