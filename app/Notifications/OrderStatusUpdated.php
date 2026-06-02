<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Update on your Order #' . $this->order->order_number)
            ->line('Your order status has been updated to: ' . strtoupper($this->order->status))
            ->line('Thank you for shopping with us!')
            ->action('View Order', url('/orders/' . $this->order->id))
            ->line('If you have any questions, feel free to contact us.');
    }
    public function toArray($notifiable)
    {
        return [
            'message' => 'Your order #' . $this->order->order_number . ' is now ' . $this->order->status,
            'order_id' => $this->order->id,
        ];
    }
}
