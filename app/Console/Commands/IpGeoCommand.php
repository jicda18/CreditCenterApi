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

        $this->line("<info>$ip:</info>");
        $this->line("   <comment>Continent: </comment>{$geo->continent}");
        $this->line("   <comment>Country: </comment>{$geo->country}");
        $this->line("   <comment>Country Code: </comment>{$geo->countryCode}");
        $this->line("   <comment>Region: </comment>{$geo->region}");
        $this->line("   <comment>Region Name: </comment>{$geo->regionName}");
        $this->line("   <comment>City: </comment>{$geo->city}");
        $this->line("   <comment>Zip: </comment>{$geo->zip}");
        $this->line("   <comment>Timezone: </comment>{$geo->timezone}");
        $this->line("   <comment>Isp: </comment>{$geo->isp}");
    }
}
