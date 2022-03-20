<?php

namespace App\Interfaces;

interface MailableInterfaces
{
    public function getMail(): string;

    public function getName(): string;

    public function getAddress(): array;
}
