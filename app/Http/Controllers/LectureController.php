<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\ListUsers;
use App\Models\Meeting;
use App\Models\User;
use App\Models\UserLecture;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        //$response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/contexts');

        $lectures = Lecture::all();
        
        return view('lectures', ['lectures' => $lectures]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lists = ListUsers::All();
        return view('create_lecture', ['lists' => $lists]);
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,JPG|max:2048'
        ], [
            'title.required' => "Don't forget to set a title for your lecture",
        ]);

        $title = $request->input('title');

        /*Store in API Collaborate:
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $body = array("name"=> $title,
                    "title"=> $title,
                    "label"=> $title,
                    "courseRoomEnabled"=> true
                );
        $url = $CSA_URL.'/contexts';
        $response = Http::withToken(env('TOKEN'))->post($url, $body);
        $data = json_decode($response, true);
        $lectureId = $data['id'];*/
        $lectureId = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz', ceil(32/strlen($x)) )),1,32);

        $skpLecture = new Lecture();
        $skpLecture->title = $title;
        $skpLecture->id = $lectureId;
        $skpLecture->list_id = $request->input("lists");

        if ($request->file==null):
            $skpLecture->photoName = "https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg";
        else:
            $skpLecture->photoName = auth()->id() . '_' . time() . '.'. $request->file->extension();  
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
            $skpLecture->photoName = $filePath;
        }

        $skpLecture->save();
        
        if($request->input("lists") <> "0") {
            $list = ListUsers::where('id',$request->input("lists"))->first();
            $users = json_decode($list['emails_list']);
            //Link users to the lecture
            foreach($users as $id => $email){
                $user = new UserLecture();
                $user->lecture_id = $lectureId;
                $user->user_id = $id;
                $user->isTeacher = false;

                $user->save();
            }
        }

        //Add the teacher

        /*$teacherId = User::where('email',$request->input("professor"))->pluck('id')->first();
        $teacher = new UserLecture();
        $teacher->lecture_id = $lectureId;
        $teacher->user_id = $teacherId;
        $teacher->isTeacher = true;
        
        $teacher->save();*/

        return redirect()->route('lectures');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $id)
    {
        $lecture = Lecture::find($id);
        $meetings = Meeting::where('lecture_id',$id)->pluck('photoName','id');

        $list_users = ListUsers::all();

        return view('detail_lecture', ['meetings' => $meetings, 'lecture' => $lecture, 'list_users' => $list_users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
        $lecture = Lecture::find($id);
        $lists = ListUsers::all();
        return view('edit_lecture', ['lecture' => $lecture, 'lists' => $lists]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function update($locale, $id, Request $request)
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';

        //Edit lecture in DB
        $lecture = Lecture::where('id', $id)->first();
        $lecture->title = $request['title'] <> '' ? $request['title'] : $lecture->title;
        
        //Update the list
        if($request['list_id'] <> '0' & $request['list_id'] <> $lecture->list_id){
            $lecture->list_id = $request['list_id'];
            $list = ListUsers::where('id',$request['list_id'])->first();
            $users = json_decode($list['emails_list']);
            $meetings = Meeting::where('lecture_id', $id)->pluck('id');
           
            $users_in_lecture = UserLecture::where('lecture_id', $id)->pluck('id');
            //Link users to the lecture
            foreach($users as $userId => $email){
                //Check wether the user is already attached
                if (!$users_in_lecture->contains($userId)){
                    
                    $user = new UserLecture();
                    $user->lecture_id = $id;
                    $user->user_id = $userId;
                    $user->isTeacher = false;
                    $user->save();

                    foreach($meetings as $meetingId){
                        $url = $CSA_URL.'/sessions/'.$meetingId.'/enrollments';
                        $body = array(
                            "userId" => $userId,
                            "launchingRole" => "participant",
                            "editingPermission" => "reader"
                        );
                        $response_user =  Http::withToken(env('TOKEN'))->post($url,$body);
                    }
                
                }
            }
        }
        $lecture->save();
        return LectureController::show($locale, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale,$id)
    {

        //Delete meetings in the lecture
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        while (null != $meetingId = Meeting::where('lecture_id',$id)->pluck('id')->first())
        {
            Http::withToken(env('TOKEN'))->delete($CSA_URL.'/sessions/'.$meetingId);
            Meeting::find($meetingId)->delete();
        }

        //Delete enrollments to the lecture
        while (null != $userLect = UserLecture::where('lecture_id',$id)->first()) 
        {
            $userLect->delete();
        } 
        
        //Delete lecture
        $lecture = Lecture::where('id',$id);
        $lecture->delete();

        return redirect()->route('lectures', $locale);
    }
}
