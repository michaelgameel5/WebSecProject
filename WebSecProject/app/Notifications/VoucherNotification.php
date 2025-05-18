<?php

namespace App\Notifications;

use App\Models\Voucher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class VoucherNotification extends Notification
{
    use Queueable;

    protected $voucher;

    /**
     * Create a new notification instance.
     */
    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
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
        Log::info('Preparing mail notification for voucher: ' . $this->voucher->code);
        
        return (new MailMessage)
            ->subject('New Voucher Available!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new voucher.')
            ->line('Voucher Code: ' . $this->voucher->code)
            ->line('Discount: ' . $this->voucher->discount_percentage . '%')
            ->line('Expires: ' . $this->voucher->expires_at->format('Y-m-d'))
            ->action('Use Voucher', url('/cart'))
            ->line('Thank you for using our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        Log::info('Preparing database notification for voucher: ' . $this->voucher->code);
        
        return [
            'voucher_id' => $this->voucher->id,
            'code' => $this->voucher->code,
            'discount_percentage' => $this->voucher->discount_percentage,
            'expires_at' => $this->voucher->expires_at->format('Y-m-d'),
            'message' => 'You have received a new voucher with ' . $this->voucher->discount_percentage . '% discount!'
        ];
    }
} 