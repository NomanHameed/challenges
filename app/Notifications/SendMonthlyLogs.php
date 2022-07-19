<?php

namespace App\Notifications;

use App\Http\PasswordResetController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendMonthlyLogs extends Notification
{
    use Queueable;

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
     * Create a new notification instance.
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
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello Admin!')
            ->subject('The user '.$this->userName.' has share his monthly logs')
            ->line($this->userName.' has submitted their monthly log of '.$this->challengeName.' for '.$this->badgeName.'.')
            ->line('Following are the details:')
            ->line('Badge Name: '.$this->badgeName)
            ->line('Miles Complete: '.$this->distanceCovered)
            ->line('Start Date: '.$this->start_date)
            ->line('End Date: '.$this->end_date)
            ->line('Address: '.$this->userAddress)
            ->line('Mobile: '.$this->userMobileNumber)
            ->line('Email ID: '.$this->userEmail);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
