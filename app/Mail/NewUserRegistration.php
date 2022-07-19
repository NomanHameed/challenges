<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewUserRegistration extends Mailable
{
    use Queueable, SerializesModels;

    protected $userName;
    protected $loginLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $loginLink)
    {
        $this->userName         = $userName;
        $this->loginLink      = $loginLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New User Registration')
                    ->with([
                        'userName' => $this->userName,
                        'loginLink' => $this->loginLink,
                    ])
                    ->view('emails.NewUserRegistration');
    }
}
