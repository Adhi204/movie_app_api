<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-storage
        {disks?* : List of disks to be cleaned}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files and folders in the public storage folder';

    /**
     * List of disks to include in the cleanup
     *
     * @var array
     */
    protected $disks = [
        'images',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $disks = empty($this->argument('disks')) ? $this->disks : $this->argument('disks');

        $this->info("CLEANING UP STORAGE DISKS");
        $this->newLine();

        foreach ($disks as $disk) {
            echo ("Cleaning up disk '{$disk}' ...");

            //delete all subfolders
            foreach (Storage::disk($disk)->directories() as $directory) {
                Storage::disk($disk)->deleteDirectory($directory);
            }

            //delete all files
            foreach (Storage::disk($disk)->files() as $file) {
                if ($file != '.gitignore') {
                    Storage::disk($disk)->delete($file);
                }
            }

            echo ("DONE\n");
        }

        $this->newLine();
    }
}
