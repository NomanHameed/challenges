<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewChallengeAssigned extends Mailable
{
    use Queueable, SerializesModels;

    protected $userName;
    protected $challengeLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $challengeLink)
    {
        $this->userName         = $userName;
        $this->challengeLink    = $challengeLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Challenge Assigned')
                    ->with([
                        'userName' => $this->userName,
                        'challengeLink' => $this->challengeLink,
                    ])
                    ->view('emails.NewChallengeAssigned');
    }
}
