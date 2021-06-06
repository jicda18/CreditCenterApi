<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MigrationDataMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name  = 'make:migrationdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create migration data file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'migrationdata';

    /**
     *
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return  base_path('stubs/MigrationData.stub');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::snake(Str::replaceFirst($this->rootNamespace(), '', $name));

        return database_path('data') . '/' . date('Ymd_His_') . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['model', InputArgument::REQUIRED, 'The name of the model'],
        ];
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        $model = trim($this->argument('model'));

        return str_replace('DummyModel', $model, str_replace('DummyClass', $class, $stub));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return Str::ucfirst(Str::camel(trim($this->argument('name'))));
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $base = database_path('data');

        $files = scandir($base);
        $name = $this->getNameInput();

        foreach ($files as $file) {
            if (Str::endsWith($file, '.php')) {
                $class_name = Str::ucfirst(Str::camel(Str::substr(Str::substr($file, 0, strlen($file) - 4), 16)));

                if ($name === $class_name) {
                    $this->error("The class {$name} exists");

                    return;
                }
            }
        }

        parent::handle();
    }
}
