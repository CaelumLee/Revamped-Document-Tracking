<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;
use Auth;

class DeclineNotif extends Notification implements ShouldQueue
{
    use Queueable;

    protected $docu;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($docu)
    {
        $this->docu = $docu;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'docu_id' => $this->docu->id,
            'reference_number' => $this->docu->reference_number,
            'sender' => Auth::user()->username
        ];
    }

    public function toBroadcast($notifiable)
    {
        
        return new BroadcastMessage([
            'created_at' => Carbon::parse($this->docu->created_at)->format('Y-m-d H:i:s a'),
            'id' => $this->id,
            'data' => [
                'docu_id' => $this->docu->id,
                'reference_number' => $this->docu->reference_number,
                'sender' => Auth::user()->username
            ]
        ]);
    }
}
