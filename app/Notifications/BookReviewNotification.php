<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\BookReview;
use Illuminate\Support\Facades\Log;

class BookReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $review, // Changed from BookReview to handle both model and array
        public string $action // 'created', 'updated', or 'deleted'
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        Log::info('Building email notification', [
            'action' => $this->action,
            'review_id' => is_object($this->review) ? $this->review->id : 'N/A',
            'recipient' => $notifiable->email
        ]);

        $mail = (new MailMessage)
            ->greeting("Hello {$notifiable->name}!");

        // Handle both object and array review data
        $bookTitle = is_object($this->review) ? $this->review->book_title : $this->review['book_title'];
        $rating = is_object($this->review) ? $this->review->rating : $this->review['rating'];
        $reviewText = is_object($this->review) ? $this->review->review : $this->review['review'];

        switch ($this->action) {
            case 'created':
                $mail->subject("New Review: {$bookTitle}")
                    ->line("A new review has been added:")
                    ->line("📖 Book: {$bookTitle}")
                    ->line("⭐ Rating: " . str_repeat('⭐', $rating))
                    ->line("💬 Review: {$reviewText}");
                break;

            case 'updated':
                $mail->subject("Updated Review: {$bookTitle}")
                    ->line("A review has been updated:")
                    ->line("📖 Book: {$bookTitle}")
                    ->line("⭐ New Rating: " . str_repeat('⭐', $rating))
                    ->line("💬 Updated Review: {$reviewText}");
                break;

            case 'deleted':
                $mail->subject("Review Deleted: {$bookTitle}")
                    ->line("A review has been deleted:")
                    ->line("📖 Book: {$bookTitle}")
                    ->line("⭐ Rating Was: " . str_repeat('⭐', $rating))
                    ->line("🗑️ This review has been removed from the system");
                break;
        }

        return $mail->action('View Dashboard', url('/dashboard'))
                   ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        $bookTitle = is_object($this->review) ? $this->review->book_title : $this->review['book_title'];
        
        return [
            'action' => $this->action,
            'review_id' => is_object($this->review) ? $this->review->id : null,
            'book_title' => $bookTitle,
            'message' => "Review {$this->action}: {$bookTitle}"
        ];
    }
}