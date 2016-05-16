<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Cache;

class CalculateAverage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:average';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate crime averages';

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
      $old_average = Cache::get('crime_average');
      $new_average = \App\Crime::where('created_at', '>=', Carbon::now()->subMonths(3)->toDateTimeString())
        ->where('class', 'serious')
        ->count() / (186.8 / 0.25);
      Cache::forever('crime_average', $new_average);
      $this->info("Old Average: $old_average | New Average: $new_average");
    }
}
