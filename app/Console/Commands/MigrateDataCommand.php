<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigrateDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:data {--F|fresh : Truncate the models table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Entry the default required pending data to DB.';

    protected $truncated = [];

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
        $fresh = $this->option('fresh');
        $debug = config('app.debug');

        if ($fresh) {
            if (!$debug && !$this->confirm('Application isn\'t in debug mode. Do you wish to continue?')) {
                $this->info('Command was stopped.');
                return;
            }

            DB::statement('TRUNCATE migrations_data');
        }


        $base = database_path('data');

        $files = collect(scandir($base))->filter(function ($path) {
            if (Str::endsWith($path, '.php')) {
                $name = $this->getFilename($path);

                return DB::table('migrations_data')->where('migration', '=', $name)->count() === 0;
            }

            return false;
        })->values();

        if ($files->count() === 0) {
            $this->info('Nothing to migrate.');
        }

        $batch = DB::table('migrations_data')->select(DB::raw('IFNULL(max(batch), 0) as batch'))->get()->first()->batch + 1;
        foreach ($files->all() as $file) {
            $this->line("<comment>Migrating:</comment> {$file}");
            $startTime = microtime(true);

            require_once "$base/$file";

            $class = $this->getClassName($file);
            /**
             * @var \App\Classes\MigrationData
             */
            $migrate = new $class;

            $model = $migrate->getModel();
            $entities = $migrate->getEntities();

            if ($fresh) {
                $this->truncate($model);
            }

            foreach ($entities as $key => $entity) {
                $relations = [];

                $new = new $model;
                foreach ($entity as $key => $propriety) {
                    if (is_array($propriety)) {
                        $relations[$key] =  $propriety;
                    } else {
                        $new->{$key} = $propriety;
                    }
                }

                $new->save();

                if (count($relations) !== 0) {
                    foreach ($relations as $key => $values) {
                        $new->{$key}()->sync($values);
                    }
                }
            }

            DB::table('migrations_data')->insert(['migration' => $this->getFilename($file), 'batch' => $batch]);

            $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
            $this->line("<info>Migrated:</info> {$file} ({$runTime}ms)");
        }
    }

    protected function getFilename(string $path): string
    {
        return Str::substr($path, 0, strlen($path) - 4);
    }

    protected function getClassName(string $path): string
    {
        return Str::ucfirst(Str::camel(Str::substr($this->getFilename($path), 16)));
    }

    protected function truncate(string $model)
    {
        if (!key_exists($model, $this->truncated)) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $model::truncate();
            $this->truncated[$model] = true;
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
