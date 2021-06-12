<?php

namespace App\Console\Commands;

use App\Traits\PasswordScore;
use Illuminate\Console\Command;

class PasswordScoreCommand extends Command
{
    use PasswordScore;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:score
        {password : password string to score}
        {--U|user= : username that use the password}
        {--E|email= : email that use the password}
        {--N|name= : name that use the password}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the score to password string';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password = $this->argument('password');
        $user = $this->option('user') ?? '';
        $email = $this->option('email') ?? '';
        $name = $this->option('name') ?? '';

        $score = $this->getScore($password, $user, $name, $email);
        $warning = $score['warning'];
        $suggestions = $score['suggestions'];
        $score = $score['score'];

        $this->info("The score is: $score.");

        if ($warning !== null && $warning !== '') {
            $this->warn("$warning.");
        }

        if ($suggestions !== null && $suggestions !== '') {
            $this->line("<comment>$suggestions.</comment>");
        }

        return $score;
    }
}
