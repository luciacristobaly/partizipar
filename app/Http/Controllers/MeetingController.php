<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Lecture;
use App\Models\ListUsers;
use App\Models\User;
use App\Models\UserLecture;
use App\Mail\Mail;
use App\Traits\UploadTrait;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MeetingController extends Controller
{

    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userName)
    {
        $meetings = Meeting::all()->sortBy('dateTime');
        
        return view('home', ['meetings' => $meetings, 'userName' => $userName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lectures = Lecture::all();

        $lists = ListUsers::all();

        return view('create_meeting', ['lectures' => $lectures, 'lists' => $lists]);
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
            'title' => 'required',
            'dateTimeStart' => 'required',
            'dateTimeEnd' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => "Don't forget to set a title for your meeting",
            'dateTimeStart.required' => 'When will the meeting take place?',
            'dateTimeStart.required' => 'When will the meeting end?'
        ]);

        $sendMails = false;
        $title = $request->input('title');
        $datetime0 = (new DateTime($request->input('dateTimeStart')))->format('c');
        $datetime1 = (new DateTime($request->input('dateTimeEnd')))->format('c');


        /* Sending mails */
        $mailDetails = [];
        if ($request->input('body') <> null){
            $mailDetails = [
                'title' => $title,
                'body' => $request->input('body')
            ];
            $sendMails = true;
        }
        
        /* Store in API Collaborate: */
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $body = array("name"=> $title,
                    "startTime"=> substr($datetime0, 0, 19).'.000Z',
                    "endTime"=> substr($datetime1, 0, 19).'.000Z',
                    "allowGuest"=> true,
                    "showProfile"=> false,
                    "createdTimezone"=> "Europe/Spain",
                    "courseRoomEnabled"=> false,
                    "ltiParticipantRole"=> "participant",
                    "active"=> true,
                    "canEnableLargeSession"=> true
                );
        $url = $CSA_URL.'/sessions';
        $response = Http::withToken(env('TOKEN'))->post($url, $body);
        $data = json_decode($response, true);
        
        /* Attach meeting to a lecture */
        $users = array();
        if($request->input("lectureOwner")<>"0") {
            $users_in_lecture = UserLecture::find('lecture_id', $request->input("lectureOwner"))->pluck('user_id');
            foreach ($users_in_lecture as $id_in_lecture){
                $email_in_lecture =  User::where('id',$id_in_lecture)->pluck('email')->first();
                $users[$id_in_lecture] = $email_in_lecture;
            }
        }

        /* Add attendees to the meeting */
        $emails = str_replace(' ','',$request->input('attendee_email'));
        $emails = explode(';',$emails);
        
        if ( $emails[0] <> "" ){
            foreach ($emails as $email) {
                $userId = User::where('email',$email)->pluck('id')->first();
                if ($userId==null) 
                {
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
                    $skpUser->name = substr($email,0,strlen($email)-10);
                    $skpUser->email = $email;
                    $skpUser->id = $userId;
                    
                    $skpUser->save();
                }
                $users[$userId] = $email;
            }
        }
        
        if ($request->input("lists") <> "0") {
            $list = ListUsers::where('id',$request->input("lists"))->first();
            $users_in_list = json_decode($list['emails_list']);
            //Link users to the meeting
            foreach($users_in_list as $id => $email) $users[$id] = $email;
        }

        $url = $CSA_URL.'/sessions/'.$data['id'].'/enrollments';
        foreach ($users as $id => $email) {
            //Finding user by email
            $userId = User::where('email',$email)->pluck('id')->first();;
            $body = array(
                "userId" => $userId,
                "launchingRole" => "participant",
                "editingPermission" => "reader"
            );
            $response =  Http::withToken(env('TOKEN'))->post($url,$body);
            
            if ($sendMails){
                \Mail::to($email)->send(new \App\Mail\MailSender($mailDetails));
            }
        }
        
        /* Create Meeting in sqlite */
        $skpMeeting = new Meeting();
        $skpMeeting->title = $title;
        $skpMeeting->dateTime = $datetime0;
        $skpMeeting->lecture_id = $request->input('lectureOwner');
        $skpMeeting->list_id = $request->input('lists');
        $skpMeeting->body = $request->input('body')<>null ? $request->input('body') : '';
        
        $skpMeeting->id = $data['id'];
        if ($request->file==null & $skpMeeting->lecture_id == '0')
        {
            $skpMeeting->photoName = "https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg";
        }
        elseif ($skpMeeting->lecture_id <> '0')
        {
            $lecturePhoto = Lecture::where('id',$skpMeeting['lecture_id'])->pluck('photoName');
            $skpMeeting->photoName = $lecturePhoto[0];
        }
        else
        {
            $skpMeeting->photoName = auth()->id() . '_' . time() . '.'. $request->file->extension();  
        }
        

        /* Storage image */
        if ($request->has('image')) {
            // Get image file
            $image = $request->file('image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($request->input('name')).'_'.time();
            // Define folder path
            $folder = '/uploads/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user profile image path in database to filePath
            $skpMeeting->photoName = $filePath;
        }

        $skpMeeting->save();

        
        return redirect()->route('home', app()->getLocale());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(String $locale, String $id)
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';

        $url = $CSA_URL.'/sessions/'.$id;
        $response = Http::withToken(env('TOKEN'))->get($url);
        $attendees = Http::withToken(env('TOKEN'))->get($url.'/enrollments');

        $lecture_id = Meeting::where('id',$id)->pluck('lecture_id')->first();
        $lecture = $lecture_id <> '0' ? Lecture::where('id', $lecture_id)->first() : null;

        $list_lectures = Lecture::all();
        $list_users = ListUsers::all();
 
        return view('detail_meeting', ['meeting' => $response, 'attendees' => $attendees, 
                    'lecture' => $lecture, 'list_lectures' => $list_lectures, 'list_users' => $list_users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
        /*$CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/contexts');
        $lectures = $response ['results'];*/
        $lectures = Lecture::all();

        $lists = ListUsers::all();

        $meeting = Meeting::where('id',$id)->first();
        return view('edit_meeting', ['meeting' => $meeting, 'lists' => $lists, 'lectures' => $lectures]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function update($locale, $id, $enrollment, Request $request)
    {   
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        if ($enrollment <> 'edit'){
            Http::withToken(env('TOKEN'))->delete($CSA_URL.'/sessions/'.$id.'/enrollments/'.$enrollment);
        } else {
            
            //Edit meeting in DB
            $meeting = Meeting::where('id', $id)->first();
            $meeting->title = $request['title'] <> '' ? $request['title'] : $meeting->title;
            $meeting->dateTime = $request['dateTimeStart'] <> '' ? (new DateTime($request->input('dateTimeStart')))->format('c') : $meeting->dateTime;
            
            
            $users = array();
            $url = $CSA_URL.'/sessions/'.$id.'/enrollments';
            if ($request['lectureOwner'] <> '0' && $request['lectureOwner'] <> $meeting->lecture_id){
                $meeting->lecture_id = $request['lectureOwner'];
                $users_in_lecture = UserLecture::where('lecture_id', $meeting->lecture_id)->pluck('user_id');
                
                $users_in_meeting = Http::withToken(env('TOKEN'))->get($url);
                $users_in_meeting = $users_in_meeting['results'];
                
                foreach ($users_in_lecture as $user_to_meeting){
                    $exists = false;
                    //Check wether the user in the new lecture is already invited
                    foreach ($users_in_meeting as $user_in_meeting){
                        if ($user_in_meeting['userId'] == $user_to_meeting) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists){
                        $email_in_lecture =  User::where('id',$user_to_meeting)->pluck('email')->first();
                        $users[$user_to_meeting] = $email_in_lecture;
                    }
                }
            }
            
            if ($request['list_id'] <> '0' & $request['list_id'] <> $meeting->list_id){
                $users_in_list_tmp = explode(',',ListUsers::where('id',$request['list_id'])->pluck('emails_list')->first());
                $users_in_list_tmp = str_replace('"','',$users_in_list_tmp);
                $users_in_list = array();
                foreach(  $users_in_list_tmp as $u){
                    $tmp = str_replace('{','',$u);
                    $tmp = explode( ',', $tmp );
                    foreach($tmp as $t){
                        $t = explode(':', $t);
                        $users_in_list[ $t[0] ] = $t[1];
                    }
                    
                }
                $users_in_list = array_diff($users_in_list, $users);
                $users = array_merge($users,$users_in_list);
                $meeting->list_id = $request['list_id'];
            }
            $meeting->save();
            
            //Edit meeting in API
            $datetime1 =  $request['dateTimeEnd'] <> '' ? (new DateTime($request->input('dateTimeEnd')))->format('c') : $meeting->dateTime;
            $body = array(
                "name"=> $meeting->title,
                "startTime"=> substr($meeting->dateTime, 0, 19).'.000Z',
                "endTime"=> substr($datetime1, 0, 19).'.000Z',
            );
            $url = $CSA_URL.'/sessions/'.$id;
            $response = Http::withToken(env('TOKEN'))->patch($url, $body);
            
            if ( $request['email']<>""){
                // Add attendee to the meeting
                
                $userId = User::where('email',$request['email'])->pluck('id')->first();
                if ($userId==null) 
                {
                    $fullName = '';
                    $lastName = '';
                    $displayName = $request['name']<>''? $request['name'] : substr($request['email'],0,strlen($request['email'])-10);
                    if($request['name']<>''){
                        $fullName = explode(' ',$request['name']);
                        $lastName = count($fullName) > 1 ? $fullName[1] : 'Unknown';
                    }
                    $body = array(
                        "lastName"=> $lastName,
                        "firstName"=> $fullName[0],
                        "displayName"=> $displayName,
                        "email"=> $request['email']
                    );
                    
                    $url = $CSA_URL.'/users';
                    $user = Http::withToken(env('TOKEN'))->post($url,$body);
                    $userId = $user['id'];
                    
                    $skpUser = new User();
                    $skpUser->name = $displayName;
                    $skpUser->email = $request['email'];
                    $skpUser->id = $userId;
                    $skpUser->save();
                }
                
                $users[$userId] = $request['email'];
            }

            //Link users to the meeting
            foreach($users as $u_id => $email){
                $body = array(
                    "userId" => $u_id,
                    "launchingRole" => "participant",
                    "editingPermission" => "reader"
                );
                $url = $CSA_URL.'/sessions/'.$id.'/enrollments';
                $response =  Http::withToken(env('TOKEN'))->post($url,$body);
                
                if ( $meeting->body <> '' ){
                    $mailDetails = [
                        'title' => $meeting->title,
                        'body' => $meeting->body
                    ];
                    \Mail::to($email)->send(new \App\Mail\MailSender($mailDetails));
                }
            }
        }
        return MeetingController::show($locale, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        Http::withToken(env('TOKEN'))->delete($CSA_URL.'/sessions/'.$id);

        Meeting::find($id)->delete();
        return redirect()->route('home', app()->getLocale());
    }

    public function fetch()
    {
        $response = Http::get('',[
            'apiKey' => 'zaragoza_eu_rest_production',
            'limit' => 10,
        ]);
    }
}
