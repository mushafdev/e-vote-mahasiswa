<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Formatter;

class SendEmailNotifikasi extends Mailable
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
        ->from($address = 'noreply@iaisulsel.org', $name = 'Notifikasi Pendaftar '.Formatter::identity()['title'].'')
        ->subject($this->metaMail['judul'])
        ->view('a.email.notifikasi')->with([
            'nama_penerima' => $this->metaMail['nama_penerima'],
            'nama' => $this->metaMail['nama'],
            'no_anggota' => $this->metaMail['no_anggota']
        ]);

        // return $this->view('a.email.pesan')->with([
        //     'peserta' => ''
        // ]);
    }
}
