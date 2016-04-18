<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Report extends Controller
{

    public function index(\Illuminate\Http\Request $request) {
        return view('report');
    }

}
