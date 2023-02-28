<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RemindersNotification extends Notification
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
        //return ['mail'];
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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

    public function toArray($notifiable)
    {
        return [
            'type' => $this->notification['type'],
            'subject' => $this->notification['subject'],
            'from_user' => $this->notification['from_user'],
            'data' => $this->notification['data'],
            'related_id' => $this->notification['related_id'],
            'related_document' => $this->notification['related_document'], // added for compatibility purpose only(no document to track)
            'alert_level' => $this->notification['alert_level'] // added for compatibility purpose only
        ];
    }
}
