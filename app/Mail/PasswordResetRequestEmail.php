<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\PasswordRequestReset;
use App\User;

class PasswordResetRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    public $recovery;

    public function __construct(User $user, PasswordRequestReset $recovery)
    {
        $this->user = $user;
        $this->recovery = $recovery;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password_reset_request')
                    ->from('steve@hozulinks.com');
    }
}
