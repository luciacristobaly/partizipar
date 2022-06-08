<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Lecture;
use App\Models\ListUsers;
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
    public function index()
    {
        $userName = "Alumno";

        $meetings = Meeting::all()->sortByDesc('dateTime');
        
        return view('home', ['meetings' => $meetings, 'userName' => $userName]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/contexts');
        $lectures = $response ['results'];

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
            //'manager' => 'manager|email'
        ], [
            'title.required' => 'Don\'t forget to set a title for your meeting',
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
                    "startTime"=> $datetime0,
                    "endTime"=> $datetime1,
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
        
        /* Attach meeting to a lecture (session to a context) */
        if($request->input("lectureOwner")<>"0") {
            $body = array("id" => $data['id']);
            $url = $CSA_URL.'/contexts/'.$request->input('lectureOwner').'/sessions';
    
            Http::withToken(env('TOKEN'))->post($url,$body);
        }

        /* Add attendees to the meeting */
        $url = $CSA_URL.'/sessions/'.$data['id'].'/enrollments';
        $email = $request->input('attendee_email');
        if ($email != null) {
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
        if ($request->input("lists") <> "0") {
            $list = ListUsers::where('id',$request->input("lists"))->first();
            $users = json_decode($list['emails_list']);
            //Link users to the meeting
            $ids = array();
            foreach($users as $id => $email){
                $body = array(
                    "userId" => $id,
                    "launchingRole" => "participant",
                    "editingPermission" => "reader"
                );
                $response =  Http::withToken(env('TOKEN'))->post($url,$body);

                if ($sendMails){
                    \Mail::to($email)->send(new \App\Mail\MailSender($mailDetails));
                }
            }
        }
        
        /* Create Meeting in sqlite */
        $skpMeeting = new Meeting();
        $skpMeeting->title = $title;
        $skpMeeting->dateTime = $datetime0;
        $skpMeeting->lecture_id = $request->input('lectureOwner');
        
        $skpMeeting->id = $data['id'];
        if ($request->file==null):
            $skpMeeting->photoName = "https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg";
        else:
            $skpMeeting->photoName = auth()->id() . '_' . time() . '.'. $request->file->extension();  
        endif;

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

        
        return MeetingController::index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';

        $url = $CSA_URL.'/sessions/'.$id;
        $response = Http::withToken(env('TOKEN'))->get($url);
        $attendees = Http::withToken(env('TOKEN'))->get($url.'/enrollments');
 

        return view('detail_meeting', ['meeting' => $response, 'attendees' => $attendees]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/contexts');
        $lectures = $response ['results'];

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
    public function update(Request $request, $id)
    {
        //$data = $request->all();
        //$id->update($data);
        return "Meeting ".$id." updated";//response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Meeting::find($id)->delete();
        return "delete meeting ".$id;//response()->json(null, 204);
    }

    public function fetch()
    {
        $response = Http::get('',[
            'apiKey' => 'zaragoza_eu_rest_production',
            'limit' => 10,
        ]);
    }
}
