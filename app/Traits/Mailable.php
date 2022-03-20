<?php

namespace App\Traits;

trait Mailable
{
    public function getMail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->fullname;
    }

    public function getAddress(): array
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getMail(),
        ];
    }
}
