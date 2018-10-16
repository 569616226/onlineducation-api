<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearReidsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:redis_cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear project redis cache';

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
     * @return mixed
     */
    public function handle()
    {

        $result = Cache::flush();

        if ($result) {
            $this->info('Project redis cache success');
        } else {
            $this->error('php artisan clear:redis_cache');
        }
    }
}
