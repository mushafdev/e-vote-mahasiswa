<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Formatter;

class SendEmailBuktiBalon extends Mailable
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
        ->view('a.email.bukti_pilih_balon')->with([
            'nama' => $this->metaMail['nama'],
            'no_anggota' => $this->metaMail['no_anggota'],
            'waktu' => $this->metaMail['waktu'],
            'balon' => $this->metaMail['balon'],
            'no_anggota_balon' => $this->metaMail['no_anggota_balon'],
            'no_urut_balon' => $this->metaMail['no_urut_balon'],
            'judul' => $this->metaMail['judul'],
            'image_url' => url('assets/images/logo-full.png'),
        ]);

        // return $this->view('a.email.pesan')->with([
        //     'peserta' => ''
        // ]);
    }
}
