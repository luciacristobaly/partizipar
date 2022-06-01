<?php

namespace App\Http\Controllers;

use App\Models\ListUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $lists = ListUsers::all();
        
        return view('lists', ['lists' => $lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'emails' => 'required'
        ], [
            'name.required' => 'How are you going to name your list?',
            'emails.required' => 'Who will be part of your list?'
        ]);

        $emails = str_replace(' ','',$request->input('emails'));
        $emails = explode(';',$emails);

        $notFound = array();
        $found = array();
        foreach ($emails as $email) {
            $userId = User::where('email',$email)->pluck('id')->first();
            if ($userId==null) {
                array_push($notFound, $email);
            } else {
                $found[$userId] = $email;
            }
        }

        $list = ListUsers::create([
            'title' => $request->input('name'),
            'emails_list' => json_encode($found),
            'creator' => "Pruebas Lucia"
        ]);

        return ListUsersController::index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function show()//List $list)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function edit()//List $list)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)//, List $list)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy()//List $list)
    {
        //
    }
}
