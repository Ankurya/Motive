<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventApproveAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->link = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('tt35093@gmail.com', 'MoTiv')
            ->subject('Event approved by admin')
            ->view('event_approve')
            ->with([
                'url'   => $this->link,
                'user'   => $this->user,
                'img'   => url("public/website/images/logo.png")
            ]);
    }
}