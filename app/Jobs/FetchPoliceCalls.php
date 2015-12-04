<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;

class FetchPoliceCalls extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {

        $client = new \GuzzleHttp\Client();
        $res = $client->get("https://www.tulsapolice.org/live-calls-/police-calls-near-you.aspx");
        $data = (string) $res->getBody();

        if ($res->getStatusCode() != 200) {
            throw new \Exception('Couldn\'t retrieve crime data');
        }

        $classes = [
            'accident' => ['/coll/','/collision/','/crash/','/non inj/'],
            'serious' => [
              '/burglary/','/robbery/','/homicide/','/shooting/','/shots/',
              '/theft/','/missing/','/intrusion/','/doa/','/suicide/',
              '/holdup/','/stabbing/', '/assault/'
            ],
            'not_serious' => ['/disturbance/'],
            'drunk_driver' => ['/drunk/']
        ];

        preg_match_all('/<td .+>(.+)<\/td><td>(.+)<\/td>/sU', $data, $matches);

        $scraped_crimes = [];
        foreach ($matches[1] as $key=>$val) {
            foreach ($classes as $class=>$patterns) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, strtolower($val))) {
                        $class_val = $class;
                    }
                }
            }

            $crime = [
                'description' => $val,
                'address' => ucwords(strtolower($matches[2][$key])),
                'class' => isset($class_val) ? $class_val : 'other'
            ];

            $crimeModel = \App\Crime::firstOrCreate($crime);
            $crimeModel->active = true;
            $crimeModel->save();
            $scraped_crimes[] = $crimeModel->toArray();

            unset($class_val);
        }

        $expired = \App\Crime::where('updated_at', '<', \Carbon\Carbon::now()->subMinutes(6)->toDateTimeString())->update(['active' => false]);

        return [
            'expired'=>$expired,
            'scraped'=>count($scraped_crimes)
        ];
    }
}
