<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $keyword = Cache::remember('cache_' . $keyword, 30, function () use ($keyword) {
             return $keyword;
        });

        debug($keyword);
        // $blade = Auth::guest() ? 'welcome' : 'home';

        return view('home', [
            'keyword' => $keyword
        ]);
    }
}
