<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// class NewFollower extends Notification implements ShouldQueue
// {
//     use Queueable;

//     public $follower;

//     public function __construct(User $follower)
//     {
//         $this->follower = $follower;
//     }

//     public function via($notifiable)
//     {
//         return ['mail', 'database'];
//     }

//     public function toMail($notifiable)
//     {
//         return (new MailMessage)
//             ->subject('You have a new follower!')
//             ->greeting('Hello ' . $notifiable->name . '!')
//             ->line($this->follower->name . ' started following you on DramaVault')
//             ->action('View Profile', route('users.show', $this->follower))
//             ->line('Connect with your followers and share your drama experiences!');
//     }

//     public function toArray($notifiable)
//     {
//         return [
//             'type' => 'new_follower',
//             'follower_id' => $this->follower->id,
//             'follower_name' => $this->follower->name,
//             'follower_avatar' => $this->follower->avatar,
//             'message' => $this->follower->name . ' started following you',
//             'url' => route('users.show', $this->follower),
//         ];
//     }
// }