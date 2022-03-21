<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\UserEmailConfirmation;

class WelcomeToHozulinks extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $conf;
    public $url;

    public function __construct(User $user, UserEmailConfirmation $conf, $url)
    {
        $this->user = $user;
        $this->conf = $conf;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome_to_hozulinks')
                    ->from('steve@hozulinks.com');;
    }
}
