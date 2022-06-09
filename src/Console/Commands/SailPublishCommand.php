<?php

namespace ASavenkov\SailLumen\Console\Commands;

use Illuminate\Console\Command;

class SailPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sail:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the Laravel Sail Docker files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
    	if (!file_exists($this->laravel->basePath('docker-compose.yml'))) {
    		$this->line('sail:install command should be run first!');
    		return false;
    	}
    	
    	// Custom vendor:publish
    	if (file_exists($this->laravel->basePath('vendor/laravel/sail/runtimes/8.1'))) {
    		$dockerPath = $this->laravel->basePath('docker');
    		shell_exec("rm -rf $dockerPath/");
    		
    		mkdir($this->laravel->basePath('docker'), 0755);
    		mkdir($this->laravel->basePath('docker/8.1'), 0755);
    		
    		$src = $this->laravel->basePath('vendor/laravel/sail/runtimes/8.1/*');
    		$dest = $this->laravel->basePath('docker/8.1/');
    		shell_exec("cp -r $src $dest");
    	}

        file_put_contents(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    './vendor/laravel/sail/runtimes/8.1',
                ],
                [
                    './docker/8.1',
                ],
                file_get_contents($this->laravel->basePath('docker-compose.yml'))
            )
        );
    }
}