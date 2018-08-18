<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class scheduleNotification extends Notification
{
    use Queueable;
    
    public $shipments, $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $shipments)
    {
        $shipments = $this->shipments;
        $user = $this->user;
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
        $url = url('/courier/courier/shipments/1');

        return (new MailMessage)
                    ->subject('Scheduled Shipments')
                    ->markdown('mail.scheduleNotification', ['url' => $url]);
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
