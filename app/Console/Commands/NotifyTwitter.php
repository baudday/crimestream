<?php

namespace App\Console\Commands;

use App\Jobs\TweetCalls;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class NotifyTwitter extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for tweeting the most recent serious calls';

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
        $count = $this->dispatch(new TweetCalls());
        $this->info("Tweeted $count serious calls.");
    }
}
