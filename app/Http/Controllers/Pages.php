<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Pages extends Controller
{

    public function home(Request $request)
    {
        if (\Auth::check() && \Auth::user()->subscribed('main')) {
          return view('map');
        }

        return view('splash');
    }

    public function about()
    {
        return view('about');
    }

    public function map()
    {
      return view('map');
    }

    public function trial(Request $request)
    {
      $request->session()->flash('trial_msg', 'Please register to begin your free 7-day trial');
      return view('auth/register');
    }

}
