<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('MIGRATING AND SEEDING DATABASES');
        $this->call('migrate:fresh', ['--seed' => true]);

        // Create symbolic link from storage folder to public path 'storage'
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        //Cleanup disks
        $this->call('app:clean-storage');

        $this->info('Application initialized successfully.');
        $this->newLine();
    }
}
