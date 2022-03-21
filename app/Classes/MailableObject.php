<?php

namespace App\Classes;

use App\Interfaces\MailableInterfaces;
use App\Traits\Mailable;

class MailableObject implements MailableInterfaces
{
    use Mailable;

    public $fullname;
    public $email;

    public function __construct(string $fullname, string $email)
    {
        $this->fullname = $fullname;
        $this->email = $email;
    }
}
