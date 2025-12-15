<?php

namespace App\Console\Commands;

use App\Models\Spells\Spell;
use App\Services\FeToolsService;
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
    protected $signature = 'app:import-fe {file}';

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
        $file = $this->argument('file');

        if (Storage::disk('data')->missing($file)) {
            throw new FileNotFoundException('storage/data/' . $file . ' not found');
        }

        $json = json_decode(Storage::disk('data')->get($file), true);
        $spell = Spell::from5eJson($json);
        print "Spell imported: {$spell->name}" . PHP_EOL;
    }
}
