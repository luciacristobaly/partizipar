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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        $response = Http::withToken(env('TOKEN'))->get($CSA_URL.'/contexts');
        $lectures = $response ['results'];

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
            'professor' => 'required',//|email',
            //'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Don\'t forget to set a title for your lecture',
            'professor.required' => 'Who is the director of this lecture?'
        ]);

        $title = $request->input('title');

        //Store in API Collaborate:
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $body = array("name"=> $title,
                    "title"=> $title,
                    "label"=> $title,
                    "courseRoomEnabled"=> true
                );
        $url = $CSA_URL.'/contexts';
        $response = Http::withToken(env('TOKEN'))->post($url, $body);
        $data = json_decode($response, true);

        $skpLecture = new Lecture();
        $skpLecture->title = $title;
        $skpLecture->id = $data['id'];

        // Storage image 
        /*if ($request->has('image')) {
            $image = $request->file('image');
            $name = Str::slug($request->input('name')).'_'.time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $skpMeeting->photoName = $filePath;
        }*/

        $skpLecture->save();
        
        if($request->input("lists") <> "0") {
            $list = ListUsers::where('id',$request->input("lists"))->first();
            $users = json_decode($list['emails_list']);
            //Link users to the lecture
            foreach($users as $id => $email){
                $user = new UserLecture();
                $user->lecture_id = $data['id'];
                $user->user_id = $id;
                $user->isTeacher = false;

                $user->save();
            }
        }

        //Add the teacher

        $teacherId = User::where('email',$request->input("professor"))->pluck('id')->first();
        $teacher = new UserLecture();
        $teacher->lecture_id = $data['id'];
        $teacher->user_id = $teacherId;
        $teacher->isTeacher = true;
        
        $teacher->save();

        return LectureController::index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lecture = Lecture::find($id);
        $meetings = Meeting::where('lecture_id',$id)->pluck('id');

        return view('detail_lecture', ['meetings' => $meetings, 'lecture' => $lecture]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecture $lecture)
    {
        return view('lecture.edit', ['lecture' => $lecture]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lecture $lecture)
    {
        $data = $request->all();
        $lecture->update($data);
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecture $lecture)
    {
        $lecture->delete();
        return redirect('/');
    }
}
