<?php

namespace App\Console\Commands;

use App\Classes\AccesslistManagerCommand;

class BlacklistListCommand extends AccesslistManagerCommand
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(true, 'list');
    }
}
