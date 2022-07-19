<?php

namespace App\Notifications;

use App\Http\PasswordResetController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetSuccess extends Notification
{
    use Queueable;
    
    protected $userFirstName;
    protected $organisation_name;
    protected $organisation_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userFirstName, $organisation_name, $organisation_id)
    {
        $this->userFirstName = $userFirstName;
        $this->organisation_name = $organisation_name;
        $this->organisation_id = $organisation_id;
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
        if($this->organisation_name && $this->organisation_id){
            return (new MailMessage)
            ->from(env('FROM_EMAIL'), $this->organisation_name)
            ->subject('🔏 Password Reset Success')
            ->greeting('Hello '.$this->userFirstName.',')
            ->line('Your '.$this->organisation_name.' account password has been changed successfully.')
            ->line('If you did not request the password change please contact support@nubel.co.uk.')
            ->markdown(
                    'vendor.notifications.email',
                    ["organisation_id" => $this->organisation_id, "organisation_name" => $this->organisation_name]
                );
        }else{
            return (new MailMessage)
            ->from(env('FROM_EMAIL'))
            ->subject('🔏 Password Reset Success')
            ->greeting('Hello '.$this->userFirstName.',')
            ->line('Your account password has been changed successfully.')
            ->line('If you did not request the password change please contact support@nubel.co.uk.')
            ->markdown(
                    'vendor.notifications.email',
                    ["organisation_id" => '', "organisation_name" => '']
                );
        }
        
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
