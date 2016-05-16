<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SubscriptionsController extends Controller
{
    /*
     * Subscribe user
     */
    public function create(Request $request)
    {
        $user = \Auth::user();
        $creditCardToken = $request->input('stripeToken');
        $user->newSubscription('main', 'cs-basic')
          ->trialDays(7)
          ->create($creditCardToken);
        $request->session()->flash('update_success', 'Thanks for trying CrimeStream! You will be billed after your 7-day trial has ended on ' . date('M d, Y', strtotime($user->subscription('main')->trial_ends_at)));
        return redirect('/account');
    }

    /*
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        \Auth::user()->subscription('main')->cancel();
        $request->session()->flash('update_success', 'Sorry to see you go. Renew your subscription any time!');
        return redirect('/account');
    }

    /*
     * Resume cancelled, but not expired subscription
     */
    public function resume(Request $request)
    {
        $user = \Auth::user();
        $user->subscription('main')->resume();
        $ends_at = is_null($user->subscription('main')->ends_at) ? $user->subscription('main')->trial_ends_at : $user->subscription('main')->ends_at;
        $request->session()->flash('update_success', 'Subscription resumed! You will be billed on ' . date('M d, Y', strtotime($ends_at)));
        return redirect('/account');
    }

    /*
     * Update payment info
     */
    public function update(Request $request)
    {
        $user = \Auth::user();
        $user->updateCard($request->input('stripeToken'));
        $request->session()->flash('update_success', 'Your credit card has been updated.');
        return redirect('/account');
    }
}
