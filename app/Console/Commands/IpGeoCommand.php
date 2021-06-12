<?php

namespace App\Console\Commands;

use App\Traits\IpGeo;
use Illuminate\Console\Command;

class IpGeoCommand extends Command
{
    use IpGeo;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ip:geo {ip : ip to get geo information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the geo info from ip';

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
        $ip = $this->argument('ip');

        $geo = $this->getGeoFromIp($ip);

        $this->line("Continent: <comment>{$geo->continent}</comment>");
        $this->line("Country: <comment>{$geo->country}</comment>");
        $this->line("Country Code: <comment>{$geo->countryCode}</comment>");
        $this->line("Region: <comment>{$geo->region}</comment>");
        $this->line("Region Name: <comment>{$geo->regionName}</comment>");
        $this->line("City: <comment>{$geo->city}</comment>");
        $this->line("Zip: <comment>{$geo->zip}</comment>");
        $this->line("Timezone: <comment>{$geo->timezone}</comment>");
        $this->line("Isp: <comment>{$geo->isp}</comment>");

        return $geo;
    }
}
