<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\MeetingController;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('cas.auth');
    }
    
    public function index (Request $request)
    {
        return redirect(route('home', [app()->getLocale(),'user'=>cas()->user()]));
    }
}
