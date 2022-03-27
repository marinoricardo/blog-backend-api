<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Commentario extends Mailable
{
    use Queueable, SerializesModels;


    protected $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        //
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('teste', [
            'comment' => $this->comment,
        ])
        ->subject("Comentario Novo");
    }
}
