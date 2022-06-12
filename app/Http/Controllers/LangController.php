<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LangController extends Controller
{
    public function update($locale){
        \Session::put('locale', $locale);
        return redirect()->back();
    }
}