<?php

namespace App\Classes;

use App\Classes\Abstracts\ToMuchBlacklisterChecker;

class ToMuchRequest extends ToMuchBlacklisterChecker
{
    public function __construct(int $seconds, int $limit)
    {
        parent::__construct($seconds, $limit, 'TM', 'Too Many Attempts From Your Ip Address.');
    }
}
