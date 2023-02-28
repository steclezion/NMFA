<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QC extends Notification
{
    use Queueable;
    private $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        //
        $this->notification=$notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

//     public function toDatabase($notifiable)
//     {
//         return [
//             'type' => $this->notification->type,
//             'data' => $this->notification->data,
//             'category' => $this->notification->category,
//             'alert_level' => $this->notification->alert_level,
//             'related_document' => $this->notification->related_document,
//             'remark' => $this->notification->remark,
//         ];
//     }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
//        dd($this->notification);
        return [
            'type' => $this->notification['type'],
            'data' => $this->notification['data'],
            'subject' => $this->notification['subject'],
            'alert_level' => $this->notification['alert_level'],
            'related_document' => $this->notification['related_document'],
            'remark' => $this->notification['remark'],
        ];
    }
}
