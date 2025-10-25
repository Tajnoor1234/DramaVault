<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// class CommentReplied extends Notification implements ShouldQueue
// {
//     use Queueable;

//     public $comment;
//     public $reply;

//     public function __construct(Comment $comment, Comment $reply)
//     {
//         $this->comment = $comment;
//         $this->reply = $reply;
//     }

//     public function via($notifiable)
//     {
//         return ['mail', 'database'];
//     }

//     public function toMail($notifiable)
//     {
//         return (new MailMessage)
//             ->subject('Someone replied to your comment')
//             ->greeting('Hello ' . $notifiable->name . '!')
//             ->line($this->reply->user->name . ' replied to your comment on "' . $this->comment->drama->title . '"')
//             ->line('"' . $this->reply->body . '"')
//             ->action('View Reply', route('dramas.show', $this->comment->drama->slug) . '#comment-' . $this->reply->id)
//             ->line('Thank you for using DramaVault!');
//     }

//     public function toArray($notifiable)
//     {
//         return [
//             'type' => 'comment_replied',
//             'comment_id' => $this->comment->id,
//             'reply_id' => $this->reply->id,
//             'drama_id' => $this->comment->drama->id,
//             'drama_title' => $this->comment->drama->title,
//             'replier_name' => $this->reply->user->name,
//             'replier_avatar' => $this->reply->user->avatar,
//             'message' => $this->reply->user->name . ' replied to your comment',
//             'url' => route('dramas.show', $this->comment->drama->slug) . '#comment-' . $this->reply->id,
//         ];
//     }
// }