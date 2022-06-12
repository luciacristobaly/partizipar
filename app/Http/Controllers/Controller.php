<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /*public function __construct()
    {
        $this->middleware('cas.auth');
    }
    public function index (Request $request)
    {
        //$user = cas()->user();
        //... https://github.com/subfission/cas/wiki/Methods-API
        return view('index',[
            'user'=>cas()->user()
        ]);
    }*/
}
