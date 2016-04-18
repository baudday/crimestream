<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Alert extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crimes = \App\Crime::where(['active' => true, 'tweeted' => true])->orderBy('created_at', 'desc')->get();
        return response()->json($crimes);
    }


}
