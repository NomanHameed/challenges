<?php

namespace App\Notifications;

use App\Http\PasswordResetController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserPasswordSendSuccessfully extends Notification
{
    use Queueable;

    protected $password;
    protected $email;
    protected $organisation_id;
    protected $organisation_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password, $email, $organisation_id, $organisation_name)
    {
        $this->password = $password;
        $this->email = $email;
        $this->organisation_id = $organisation_id;
        $this->organisation_name = $organisation_name;
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
            ->from(env('FROM_EMAIL'), $this->organisation_name)
            ->line('Your user account has been created successfully.')
            ->line('Your new password: '.$this->password)
            ->markdown(
                    'vendor.notifications.email',
                    ["organisation_id" => $this->organisation_id, "organisation_name" => $this->organisation_name]
                );
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
