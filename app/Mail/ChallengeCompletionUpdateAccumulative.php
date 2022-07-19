<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChallengeCompletionUpdateAccumulative extends Mailable
{
    use Queueable, SerializesModels;

    protected $challengeName;
    protected $milesRestToComplete;
    protected $startDate;
    protected $endDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($challengeName, $milesRestToComplete, $startDate, $endDate)
    {
        $this->challengeName    = $challengeName;
        $this->milesRestToComplete    = $milesRestToComplete;
        $this->startDate    = $startDate;
        $this->endDate    = $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Challenge Completion Update')
                    ->with([
                        'challengeName' => $this->challengeName,
                        'milesRestToComplete' => $this->milesRestToComplete,
                        'startDate' => $this->startDate,
                        'endDate' => $this->endDate,
                    ])
                    ->view('emails.ChallengeCompletionUpdateAccumulative');
    }
}
