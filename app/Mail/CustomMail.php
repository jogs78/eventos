<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $subject;
    public $message;
    public $from;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user = null, $subject, $message, $from = null)
    {
        if($from == null){
            $from = config("mail.from");
        }

        $this->from = $from;
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.custom')->subject($this->subject)->from($this->from['address'], $this->from['name']);
    }
}
