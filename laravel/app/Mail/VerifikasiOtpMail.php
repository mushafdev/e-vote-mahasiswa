<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Formatter;

class VerifikasiOtpMail extends Mailable
{
    use Queueable, SerializesModels;
    public $metaMail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($metaMail)
    {
        $this->metaMail = $metaMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
        ->from($address = 'noreply@iaisulsel.org', $name = Formatter::identity()['title'])
        ->subject($this->metaMail['judul'])
        ->view('a.email.verifikasi_otp')->with([
            'nama' => $this->metaMail['nama'],
            'otp_code' => $this->metaMail['otp_code'],
            'time' => $this->metaMail['time'],
            'id' => $this->metaMail['id']
        ]);


    }
}
