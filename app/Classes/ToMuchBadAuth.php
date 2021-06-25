<?php

namespace App\Classes;

use App\Classes\Abstracts\ToMuchBlacklisterChecker;

class ToMuchBadAuth extends ToMuchBlacklisterChecker
{
    public function __construct(int $seconds, int $limit)
    {
        parent::__construct($seconds, $limit, 'BA', 'Too Many Bad Authentication Request From Your Ip Address.');
    }
}
