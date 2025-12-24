<?php

namespace App\Console\Commands;

use App\Models\Spells\Spell;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class ImportFe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-fe
                            {--T|type=auto}
                            {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = $this->argument('path');

        if (Storage::disk('data')->missing($path)) {
            throw new FileNotFoundException('storage/data/' . $path . ' not found');
        }

        if (is_dir(Storage::disk('data')->path($path))) {
            $files = Storage::disk('data')->files($path);
            print "Found " . count($files) . " file(s) in {$path}" . PHP_EOL;

            foreach ($files as $file) {
                print "Importing {$file}" . PHP_EOL;
            }
        } else {
            print "Importing {$path}" . PHP_EOL;
        }
    }
}
