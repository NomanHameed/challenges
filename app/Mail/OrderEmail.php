<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_data;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order_data)
    {
        $this->order_data = $order_data;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sender@example.com')
                    ->subject('Virtual Challenge: Order Status')
                    ->markdown('mails.order')
                    ->with($this->order_data);
                    /*
                      ->attach(public_path('/images').'/demo.jpg', [
                              'as' => 'demo.jpg',
                              'mime' => 'image/jpeg',
                      ]);
                    */
    }
}
