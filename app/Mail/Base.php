<?php

namespace App\Mail;

use App\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class Base extends Mailable
{
    use Queueable, SerializesModels;

    public $from_name;
    public $subject;

    public function __construct(string $subject)
    {
        $this->subject = $subject;
        $this->from_name = $from_name ?? env('MAIL_FROM_NAME');

        $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }
}
