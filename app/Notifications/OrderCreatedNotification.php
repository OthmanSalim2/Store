<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // return ['mail', 'database', 'broadcast'];
        return ['database', 'broadcast'];

        $channels = ['database'];

        if ($notifiable->notification_preferences['order_created']['sms'] ?? false) {
            $channels[] = 'vonage';
        }

        if ($notifiable->notification_preferences['order_created']['mail'] ?? false) {
            $channels[] = 'mail';
        }
        if ($notifiable->notification_preferences['order_created']['broadcast'] ?? false) {
            $channels[] = 'broadcast';
        }

        return $channels;
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $addr = $this->order->billingAddress;

        return (new MailMessage)
            ->subject("New Order #  {$this->order->number} ")
            // if don't write this will take default from .env file
            ->from('notification@othman-store.ps', 'Othman Store')
            // this's default template
            ->greeting("Hi, {$notifiable->name}")
            ->line('The introduction to the notification.')
            ->line("A new order #  {$this->order->number} created by {$addr->name} from {$addr->country_name}")
            ->action('Notification Action', url('/dashboard'))
            ->line('Thank you for using our application!')
            // This's to use specific template
            // ->view()
        ;
    }

    public function toDatabase($notifiable)
    {
        $addr = $this->order->billingAddress;
        return [
            'body' => "A new order #  {$this->order->number} created by {$addr->name} from {$addr->country_name}",
            // this's class of icon
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $addr = $this->order->billingAddress;
        // or possible not send object this's normal
        return new BroadcastMessage([
            'body' => "A new order #  {$this->order->number} created by {$addr->name} from {$addr->country_name}",
            // this's class of icon
            'icon' => 'fas fa-file',
            'url' => url('/dashboard'),
            'order_id' => $this->order->id,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
