@extends('layout')

@section('title', 'Lecture')

@section('content')


<!-- list all the meetings -->
<div class="row d-flex"> 
    <div class="col"> 
        <h3> 
            {{ strlen($lecture->title)>34 ? substr($lecture->title, 0, 30).'...' : $lecture->title }} 
            <a href="{{ route('lecture.edit', [app()->getLocale(), $lecture['id']]) }}" id="edit" name="edit"><i class="fa fa-pencil pencil-icon pr-1 text-white"></i></a></div>
        </h3> 
    </div>
</div>
<div class="row">
    <div class="col">
        <h4> @lang('Meetings'): </h4>
    </div>
</div>
@if(count($meetings)>0)
<div class="row container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
                @foreach ($meetings as $meeting_id => $meeting_photo)
                @csrf
                <div class="card mb-3 bg-dark">
                    <a href="{{ route('meeting.show', [app()->getLocale(), $meeting_id]) }}" class="stretched-link text-white"></a>
                    @if ($meeting_photo <> null)
                        <img class="card-img-top" src="{{ url($meeting_photo) }}" alt="Foto de la reunión"/>
                    @else
                        <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura"/>
                    @endif
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <p class="text-white">
                                <?php 
                                    $response = Http::withToken(env('TOKEN'))->get('https://eu.bbcollab.com/collab/api/csa/sessions/'.$meeting_id); 
                                    echo strlen($response['name'])>34 ? substr($response['name'], 0, 30).'...' : $response['name'];
                                ?>
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p style="padding-bottom: 2px">{{ $response['startTime'] }}</p>
                            </div>
                        </div>
                    </div>
                </div> 
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="row text-center">
    <div class="col">
        <p> @lang("This lecture doesn't have any meeting"). </p>
    </div>
</div>
@endif
<div class="footer">
    <button onclick="history.back(-1)" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</button>
</div>
@endsection