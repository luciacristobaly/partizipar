@extends('layout')

@section('title', 'Lecture')

@section('content')


<!-- list all the meetings -->
<div class="row"> 
    <h3> {{ strlen($lecture->title)>34 ? substr($lecture->title, 0, 30).'...' : $lecture->title }} </h3>
</div>
<div class="row">
    <h4> @lang('Meetings'): </h4>
</div>
<div class="row container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
                @forelse ($meetings as $meeting)
                @csrf
                <div class="card mb-3 bg-dark">
                    <a href="{{ route('meeting.show', $meeting) }}" class="stretched-link text-white"></a>
                    @if ($lecture['photoName'])
                        <img class="card-img-top" src="{{ url($meeting['photoName']) }}" alt="Foto de la asignatura"/>
                    @else
                        <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura"/>
                    @endif
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <p class="text-white">
                                <?php 
                                    $response = Http::withToken(env('TOKEN'))->get('https://eu.bbcollab.com/collab/api/csa/sessions/'.$meeting); 
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
                @empty
                <h4>@lang("This lecture doesn't have any meeting.")</h4>
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <button onclick="history.back(-1)" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</button>
</div>
@endsection