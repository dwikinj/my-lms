<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderComplete extends Notification
{
    use Queueable;

    protected $orderData;

    /**
     * Create a new notification instance.
     */
    public function __construct($orderData = null)
    {
        $this->orderData = $orderData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('You have a new order enrollment.')
                    ->line($this->orderData['message'] ?? 'New enrollment notification')
                    ->action('View Dashboard', url('/instructor/dashboard'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->orderData['message'] ?? 'New COD Enrollment In Course',
            'order_id' => $this->orderData['order_id'] ?? null,
            'course_title' => $this->orderData['course_title'] ?? null,
            'user_id' => $this->orderData['user_id'] ?? null,
            'price' => $this->orderData['price'] ?? null,
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}