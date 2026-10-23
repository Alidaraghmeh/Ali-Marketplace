<?php

namespace App\Notifications;

use App\Models\SellerOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerOrderShippedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(

      public SellerOrder $sellerOrder
    )
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
             ->subject('Your Order has been shipped')
            ->line($this->sellerOrder->id)
            ->action('View My Order', route('orders.index'))
            ->line('Thank you for Use My website')   ;  
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'seller_order_id'=>$this->sellerOrder->id,
            'message'=>'Your order has been shipped.'
        ];
    }
}
