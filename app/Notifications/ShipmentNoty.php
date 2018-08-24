<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ShipmentNoty extends Notification
{
    use Queueable;


	// public $thread;
	public $shipment;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($shipment) {
		// $this->thread = $thread;
		$this->shipment = $shipment;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
		return ['database'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toDatabase($notifiable) {
		return [
			'shipment' => $this->shipment,
			// 'user' => $notifiable,
		];
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
