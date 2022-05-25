<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        
        return view('lectures', ['lectures' => $lectures]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create_lecture');
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
            'professor' => 'required',//|email'
        ], [
            'title.required' => 'Don\'t forget to set a title for your lecture',
            'professor.required' => 'Who is the director of this lecture?'
        ]);

        $title = $request->input('title');
        $datetime0 = $request->input('dateTimeStart');
        $datetime1 = $request->input('dateTimeEnd');

        //Store in API Collaborate:
        $CSA_URL = 'https://eu.bbcollab.com/collab/api/csa';
        
        $body = array("name"=> $title,
                    "title"=> $title,
                    "label"=> $title,
                    "courseRoomEnabled"=> true
                );
        $url = $CSA_URL.'/contexts';
        //$response = Http::withToken(env('TOKEN'))->post($url, $body);
        //$data = json_decode($response, true);


        $skpLecture = new Lecture();
        $skpLecture->title = $title;
        //$skpLecture->id = $data['id'];
        $skpLecture->id = "a4f93138abc046cebbd4428843c602b3";
        $skpLecture->save();
        return LectureController::index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecture  $lecture
     * @return \Illuminate\Http\Response
     */
    public function show(Lecture $lecture)
    {
        $lecture = Lecture::find($lecture->id);
        return view('/');
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
