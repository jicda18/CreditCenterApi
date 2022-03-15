<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        send_email(
            'Josue',
            'Email de prueba del diseño.',
            'Quiere la boca exhausta vid, kiwi, piña y fugaz jamón. Fabio me exige, sin tapujos, que añada cerveza al whisky. Jovencillo emponzoñado de whisky, ¡qué figurota exhibes! La cigüeña tocaba cada vez mejor el saxofón y el búho pedía kiwi y queso. El jefe buscó el éxtasis en un imprevisto baño de whisky y gozó como un duque.'
                . "\n\n" . 'Exhíbanse politiquillos zafios, con orejas kilométricas y uñas de gavilán. El cadáver de Wamba, rey godo de España, fue exhumado y trasladado en una caja de zinc que pesó un kilo. El pingüino Wenceslao hizo kilómetros bajo exhaustiva lluvia y frío, añoraba a su querido cachorro. El veloz murciélago hindú comía feliz cardillo y kiwi.'
                . "\n\n" . 'La cigüeña tocaba el saxofón detrás del palenque de paja. Quiere la boca exhausta vid, kiwi, piña y fugaz jamón. Fabio me exige, sin tapujos, que añada cerveza al whisky. Jovencillo emponzoñado de whisky, ¡qué figurota exhibes! La cigüeña tocaba cada vez mejor el saxofón y el búho pedía kiwi y queso.'
                . "\n\n" . '@component(\'mail::button\', [\'url\' => \'https://google.com\', \'color\' => \'error\'])'
                . "\nboton de prueba"
                . "\n" . '@endcomponent',
            [['email' => 'josuecruz13@outlook.com', 'name' => 'Josue Cruz']]
        );
    }
}
