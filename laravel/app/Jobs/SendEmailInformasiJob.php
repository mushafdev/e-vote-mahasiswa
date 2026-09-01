<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendEmailInformasi;
use Illuminate\Support\Facades\Mail;

class SendEmailInformasiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $metaMail;
    public $mailTo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailTo,$metaMail)
    {
        $this->mailTo = $mailTo;
        $this->metaMail = $metaMail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmailInformasi($this->metaMail);
        Mail::to($this->mailTo)->send($email);
    }
}
