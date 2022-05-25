<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Lecture;
use App\Mail\Mail;
use App\Traits\UploadTrait;
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

        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/sessions');
        $meetings = $response ['results'];

        $meetings = Meeting::all();
        
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
        
        $response = Http::withToken(env(env('TOKEN')))->get($CSA_URL.'/contexts');
        $lectures = $response ['results'];

        return view('create_meeting', ['lectures' => $lectures]);
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

        $title = $request->input('title');
        $datetime0 = $request->input('dateTimeStart');
        $datetime1 = $request->input('dateTimeEnd');
        

        /*
         * Sending mails
         */
        //$mailDetails = [
        //    'title' => $title,
        //    'body' => $request->input('body')
        //];
        //\Mail::to('luciacristobaly@gmail.com')->send(new Mail($mailDetails));

        
        //Store in API Collaborate:
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $body = array("name"=> $title,
                    "startTime"=> "2022-05-20T12:00:00.000Z",
                    "endTime"=> "2022-05-22T12:00:00.000Z",
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

        //Esto hay que ponerlo optativo
        if($request->input("lectureOwner")<>"0") {
            $body = array("id" => $data['id']);
            $url = $CSA_URL.'/contexts/'.$request->input('lectureOwner').'/sessions';
    
            $response = Http::withToken(env('TOKEN'))->post($url,$body);
        }
        
        $skpMeeting = new Meeting();
        $skpMeeting->title = $title;
        $skpMeeting->dateTime = $datetime0;
        
        $skpMeeting->id = $data['id'];
        if ($request->file==null):
            $skpMeeting->photoName = "https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg";
        else:
            $skpMeeting->photoName = auth()->id() . '_' . time() . '.'. $request->file->extension();  
        endif;

        //Storage image
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

        return view('detail_meeting', ['meeting' => $response]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('meetings.edit', ['meeting' => $meeting]);
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
        $data = $request->all();
        $id->update($data);
        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Meeting::find($id)->delete();
        return response()->json(null, 204);
    }

    public function fetch()
    {
        $response = Http::get('',[
            'apiKey' => 'zaragoza_eu_rest_production',
            'limit' => 10,
        ]);
    }
}
