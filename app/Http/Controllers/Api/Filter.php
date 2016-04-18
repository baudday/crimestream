<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Filter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$slug = $request->input('slug')) return;
        switch ($slug) {
            case 'accidents':
                $filter = [
                    ['description','like','%inj%'],
                    ['description','like','%col%'],
                ];
                break;
            case 'assaults':
                $filter = [
                    ['description','like','%aslt%'],
                    ['description','like','%assault%'],
                ];
                break;
            case 'auto-thefts':
                $filter = [
                    ['description','like','%theft%'],
                ];
                break;
            case 'burglaries':
                $filter = [
                    ['description','like','%vehicle%'],
                    ['description','like','%burglary%'],
                ];
                break;
            case 'burglaries-from-vehicles':
                $filter = [
                    ['description','like','%abandoned%'],
                    ['description','like','%vehicle%'],
                ];
                break;
            case 'disturbances':
                $filter = [
                    ['description','like','%disturbance%']
                ];
                break;
            case 'hit-runs':
                $filter = [
                    ['description','like','%hit&amp;run%']
                ];
                break;
            case 'missing-persons':
                $filter = [
                    ['description','like','%missing%']
                ];
                break;
            case 'shootings':
                $filter = [
                    ['description','like','%shot%'],
                    ['description','like','%shooting%'],
                ];
                break;
        }



        $crimes = Cache::remember($slug, 1, function() use ($filter) {
            $query = \App\Crime::select('*');
            foreach($filter as $condition){
                call_user_func_array([$query,'orWhere'],$condition);
            }
            return $query->get();
        });

        return response()->json($crimes);
    }

}
