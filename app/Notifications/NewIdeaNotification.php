<?php

namespace App\Notifications;

use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewIdeaNotification extends Notification
{
    use Queueable;

    protected $idea;

    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Có ý tưởng mới trong hệ thống')
            ->greeting('Xin chào,')
            ->line('Một ý tưởng mới vừa được gửi vào hệ thống.')
            ->line('Tiêu đề: ' . $this->idea->title)
            ->action('Xem danh sách ý tưởng', url('/ideas'))
            ->line('Vui lòng kiểm tra và theo dõi ý tưởng này.');
    }
}