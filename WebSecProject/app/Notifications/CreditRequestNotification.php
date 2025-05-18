<?php

namespace App\Notifications;

use App\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreditRequestNotification extends Notification
{
    use Queueable;

    protected $card;
    protected $amount;

    /**
     * Create a new notification instance.
     */
    public function __construct(Card $card, float $amount)
    {
        $this->card = $card;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('Preparing mail notification for credit request from: ' . $this->card->user->name);
        
        return (new MailMessage)
            ->subject('New Credit Request')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new credit request has been submitted.')
            ->line('User: ' . $this->card->user->name)
            ->line('Card: **** **** **** ' . substr($this->card->card_number, -4))
            ->line('Amount: $' . number_format($this->amount, 2))
            ->action('View Request', url('/cards/credit-requests'))
            ->line('Please review this request as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        Log::info('Preparing database notification for credit request from: ' . $this->card->user->name);
        
        return [
            'card_id' => $this->card->id,
            'user_name' => $this->card->user->name,
            'card_number' => substr($this->card->card_number, -4),
            'amount' => $this->amount,
            'message' => "New credit request from {$this->card->user->name} for card ending in " . substr($this->card->card_number, -4)
        ];
    }
} 