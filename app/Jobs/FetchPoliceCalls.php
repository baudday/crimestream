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
            'serious' => ['/burglary/','/robbery/','/homicide/','/shooting/','/shots/','/theft/','/missing/','/intrusion/','/doa/','/suicide/','/holdup/','/stabbing/'],
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

            $scraped_crimes[] = $crime;
            \App\Crime::firstOrCreate($crime)->update(['active', true]);
            unset($class_val);
        }

        $active_crimes = \App\Crime::where('active', true)->select('description', 'address', 'class')->get();
        $expired_crimes = array_merge(
            array_udiff($active_crimes->toArray(), $scraped_crimes, function($a, $b) {
                return strcasecmp($a['address'], $b['address']);
            }),
            array_udiff($scraped_crimes, $active_crimes->toArray(), function ($b, $a) {
                return strcasecmp($b['address'], $a['address']);
            })
        );

        foreach ($expired_crimes as $expired_crime) {
            \App\Crime::where($expired_crime)->update(['active' => false]);
        }

        return [
            'expired'=>$expired_crimes,
            'scrapped'=>$scraped_crimes
        ];
    }
}
