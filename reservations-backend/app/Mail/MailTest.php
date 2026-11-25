<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailTest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.test')
            ->from(config('mail.from_by_brand.K') ?: config('mail.from.address'), 'Test')
            ->subject('It\'s a test');
    }
}
