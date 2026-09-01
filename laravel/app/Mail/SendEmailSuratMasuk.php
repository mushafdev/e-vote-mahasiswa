<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Formatter;

class SendEmailSuratMasuk extends Mailable
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

        if($this->metaMail['telephone']=='bidang'){

            $path=public_path('storage/files/surat_masuk/'.$this->metaMail['file']);
            return $this
            ->from($address = 'noreply@iaisulsel.org', $name = 'Surat Masuk '.Formatter::identity()['title'].'')
            ->subject($this->metaMail['judul'])
            ->view('a.email.surat_masuk_bidang')->with([
                'nama' => $this->metaMail['nama'],
                'id_surat_masuk' => $this->metaMail['id_surat_masuk']
            ])
            ->attach($path, [
                    'as' => $this->metaMail['file'],
                    'mime' => 'application/pdf',
            ]);


        }else{
            return $this
            ->from($address = 'noreply@iaisulsel.org', $name = 'Surat Masuk '.Formatter::identity()['title'].'')
            ->subject($this->metaMail['judul'])
            ->view('a.email.surat_masuk')->with([
                'nama' => $this->metaMail['nama'],
                'id_surat_masuk' => $this->metaMail['id_surat_masuk']
            ]);

        }

    }
}
