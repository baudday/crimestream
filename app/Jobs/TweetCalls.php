<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Abraham\TwitterOAuth\TwitterOAuth;

use App\Crime;

class TweetCalls extends Job implements SelfHandling
{
    private $hashtags = ['#CrimeStream', '#Tulsa', '#StaySafeTulsa'];

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
     * @return int
     */
    public function handle()
    {
        $connection = new TwitterOAuth(getenv('TWITTER_CONSUMER_KEY'), getenv('TWITTER_CONSUMER_SECRET'), getenv('TWITTER_ACCESS_TOKEN'), getenv('TWITTER_ACCESS_SECRET'));
        $content = $connection->get("account/verify_credentials");
        $exclusions = ['/vehicle/','/auto/'];
        $calls = Crime::where(['tweeted' => false, 'active' => true, 'class' => 'serious'])
                      ->get()->filter(function ($call) use ($exclusions) {
                        foreach ($exclusions as $pattern) {
                          if (preg_match($pattern, strtolower($call->description))) {
                            return false;
                          }
                        }

                        return true;
                      });

        foreach ($calls as $call) {
          $str = "$call->address - $call->description";
          $body = strlen($str) > 100 ? substr($str, 0, 97) . "..." : $str;
          $message = "ALERT: $body";
          foreach ($this->hashtags as $hashtag) {
            if (strlen($message . " $hashtag") < 140) {
              $message .= " $hashtag";
            }
          }
          $connection->post('statuses/update', ['status' => $message]);
          $call->tweeted = true;
          $call->save();
        }

        return count($calls);
    }
}
