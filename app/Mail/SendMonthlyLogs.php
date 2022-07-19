<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMonthlyLogs extends Mailable
{
    use Queueable, SerializesModels;

    protected $userName;
    protected $userAddress;
    protected $userMobileNumber;
    protected $userEmail;
    protected $challengeName;
    protected $challengeType;
    protected $distanceCovered;
    protected $start_date;
    protected $end_date;
    protected $badgeName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $userAddress, $userMobileNumber, $userEmail, $challengeName, $challengeType, $distanceCovered, $start_date, $end_date, $badgeName)
    {
        $this->userName         = $userName;
        $this->userAddress      = $userAddress;
        $this->userMobileNumber = $userMobileNumber;
        $this->userEmail        = $userEmail;
        $this->challengeName    = $challengeName;
        $this->challengeType    = $challengeType;
        $this->distanceCovered  = $distanceCovered;
        $this->start_date       = $start_date;
        $this->end_date         = $end_date;
        $this->badgeName         = $badgeName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Monthly Log Submission')
                    ->with([
                        'userName' => $this->userName,
                        'userAddress' => $this->userAddress,
                        'userMobileNumber' => $this->userMobileNumber,
                        'userEmail' => $this->userEmail,
                        'challengeName' => $this->challengeName,
                        'challengeType' => $this->challengeType,
                        'distanceCovered' => $this->distanceCovered,
                        'start_date' => $this->start_date,
                        'end_date' => $this->end_date,
                        'badgeName' => $this->badgeName,
                    ])
                    ->view('emails.SendMonthlyLogs');
    }
}
