<?php

namespace App\Console\Commands;

use App\Jobs\FetchPoliceCalls;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class DataScrape extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Central command for dispatching all scrape calls that may occur';

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
        $calls = $this->dispatch(new FetchPoliceCalls());
        $this->info(sprintf("Fetched %s calls, set %s to expired",$calls['scraped'], $calls['expired']));
    }
}
