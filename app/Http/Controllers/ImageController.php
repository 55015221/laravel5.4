<?php


namespace App\Http\Controllers;

class ImageController extends Controller
{
    const STAR_URL = 'http://www.dogstar.net/photo.php?pid=1938';

    public function index(ESignService $ESignService)
    {


    }

    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}