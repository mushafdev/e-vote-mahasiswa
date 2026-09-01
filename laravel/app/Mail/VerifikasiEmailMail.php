<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Formatter;

class VerifikasiEmailMail extends Mailable
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
        ->view('a.email.verifikasi_email')->with([
            'nama' => $this->metaMail['nama'],
            'link_verifikasi' => $this->metaMail['link_verifikasi'],
        ]);


    }
}
