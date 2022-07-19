<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuccessfulBadgeAchievement extends Mailable
{
    use Queueable, SerializesModels;

    protected $userName;
    protected $badgeName;
    protected $badgeLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $badgeName, $badgeLink)
    {
        $this->userName    = $userName;
        $this->badgeName    = $badgeName;
        $this->badgeLink    = $badgeLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Successful Badge Achievement')
                    ->with([
                        'userName' => $this->userName,
                        'badgeName' => $this->badgeName,
                        'badgeLink' => $this->badgeLink,
                        
                    ])
                    ->view('emails.SuccessfulBadgeAchievement');
    }
}
