<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class ScehduleTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dayly tast has been start.';

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
        log::info('commit one time');
    }
}
