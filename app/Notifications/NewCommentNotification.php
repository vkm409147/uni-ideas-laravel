<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ý tưởng của bạn có bình luận mới')
            ->greeting('Xin chào,')
            ->line('Ý tưởng của bạn vừa nhận được một bình luận mới.')
            ->line('Nội dung bình luận: ' . $this->comment->content)
            ->action('Xem chi tiết ý tưởng', url('/ideas/' . $this->comment->idea_id))
            ->line('Cảm ơn bạn đã sử dụng hệ thống.');
    }
}