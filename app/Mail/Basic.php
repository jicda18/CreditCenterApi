<?php

namespace App\Mail;

class Basic extends Base
{
    public $name;
    public $title;
    public $msg;
    public $attaches;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $subject, string $title, string $msg, array $attaches = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->msg = $msg;

        $this->attaches = $attaches ?? [];

        parent::__construct($subject);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->markdown('mails.basic');

        foreach ($this->attaches as $key => $attach) {
            $mail->attachFromStorageDisk($attach['disk'], $attach['log_path'], $attach['send_as']);
        }

        return $mail;
    }
}
