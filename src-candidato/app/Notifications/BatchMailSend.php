<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BatchMailSend extends Notification
{
    use Queueable;

    public $subscriptions;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subscriptions)
    {
        $this->subscriptions = $subscriptions;
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
        $count = $this->subscriptions->count();
        return (new MailMessage)
                    ->line("Pessoas notificadas $count")                    
                    ->line($this->subscriptions->implode(', '));
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
