<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\UserEmailConfirmation;

class WelcomeToHomas extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $conf;

    public function __construct(User $user, UserEmailConfirmation $conf)
    {
        $this->user = $user;
        $this->conf = $conf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcome_mail')
                    ->from('steve@homas.com');
    }
}
