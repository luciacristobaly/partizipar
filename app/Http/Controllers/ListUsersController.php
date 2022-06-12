<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\ListUsers;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        $found = array();
        foreach ($emails as $email) {
            $userId = User::where('email',$email)->pluck('id')->first();
            if ($userId==null) {
                $body = array(
                    "lastName"=> "Unknown",
                    "firstName"=> "Unknown",
                    "displayName"=> substr($email,0,strlen($email)-10),
                    "email"=> $email
                );
                $url = $CSA_URL.'/users';
                $user = Http::withToken(env('TOKEN'))->post($url,$body);
                $userId = $user['id'];
                
                $skpUser = new User();
                $skpUser->name = "Unknown";
                $skpUser->email = $email;
                $skpUser->id = $userId;

                $skpUser->save();
            }
            $found[$userId] = $email;
        }

        $list = ListUsers::create([
            'title' => $request->input('name'),
            'emails_list' => json_encode($found),
            'creator' => "Pruebas Lucia"
        ]);

        return redirect(route('lists', app()->getLocale()));
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
    public function edit($locale, $id)//List $list)
    {
        $list = ListUsers::where('id', $id)->first();
        $users = array();
        foreach (json_decode($list->emails_list) as $id=>$email)
        {
            $firstName = User::where('email',$email)->pluck('name')->first();
            $displayName =  Http::withToken(env('TOKEN'))->get('https://eu.bbcollab.com/collab/api/csa/users/'.$id);
            $name = $firstName <> "Unknown" ? $firstName : $displayName['displayName']; 
            $users[$email] = $name;
        }
        return view('edit_list', ['list' => $list, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function update($locale, $id, Request $request)
    {
        /*request()->validate([
            'email' => 'email:rfc,dns'
        ]);*/
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';

        $list = ListUsers::where('id',$id)->first();
        $emails = json_decode($list['emails_list']);
        $edited = false;

        if ( $request['title']!= null && $list['title'] <> $request['title'] ){
            $edited = true;
            $list->title = $request['title'];
        }
        $m = array();
        if( $request['name']<>null && $request['email']<>null) {

            $userId = User::where('email',$request['email'])->pluck('id')->first();
            $newEmailList = array();
            if ($userId == null)
            {
                $fullName = explode(' ',$request['name']);
                $lastName = count($fullName) > 1 ? $fullName[1] : 'Unknown';
                $body = array(
                    "lastName"=> "Unknown",
                    "firstName"=> $fullName[0],
                    "displayName"=> $request['name'],
                    "email"=> $request['email']
                );
                $url = $CSA_URL.'/users';
                $newUser = Http::withToken(env('TOKEN'))->post($url,$body);
                
                $skpUser = new User();
                $skpUser->name = $request['name'];
                $skpUser->email = $request['email'];
                $skpUser->id = $newUser['id'];

                $skpUser->save();
                
                $userId = $newUser['id'];

            }
            
            //Make sure the user won't be twice in the list
            $found = false;
            foreach ($emails as $i => $email_) {
                if ($email_ == $request['email']) {
                    $found = true;
                }
                $newEmailList[$i] = $email_;
            }
            
            //Add new user to the list
            if ( !$found ) {
                $newEmailList[$userId] = $request['email'];
                
                //Update the meetings and lectures
                $id_lectures = Lecture::where('list_id', $id)->pluck('id');
                $id_meetings = Meeting::where('list_id', $id)->pluck('id');

                foreach ($id_lectures as $id_lect) 
                {
                    $m = Meeting::where('lecture_id', $id_lect)->pluck('id');
                    $id_meetings = $id_meetings->concat($m);
                }

                foreach($id_meetings as $meetingId )
                {
                    $body = array(
                        "userId" => $userId,
                        "launchingRole" => "participant",
                        "editingPermission" => "reader"
                    );
                    $url = $CSA_URL.'/sessions/'.$meetingId.'/enrollments';
                    $response =  Http::withToken(env('TOKEN'))->post($url,$body);
    
                    //Send notification to users
                    $meeting = Meeting::where('id',$meetingId)->first();
                    if ($meeting['body'] <> null){
                        $mailDetails = [
                            'title' => $meeting['title'],
                            'body' => $meeting['body']
                        ];
                        \Mail::to($email)->send(new \App\Mail\MailSender($mailDetails));
                    }
                }
            }

            $edited = true;
            $list->emails_list = $newEmailList;
        }
        if ($edited) $list->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\List  $list
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        $response = ListUsers::where('id',$id)->delete();
        return redirect(route('lists', app()->getLocale()));
    }
}
