@extends('layout')

@section('title', 'Home')

@section('content')


<!-- list all the meetings -->
<h3>@lang('Following meetings of '){{ $userName }}</h3>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
            @forelse ($meetings as $meeting)
            @csrf
                <div class="card mb-3 bg-dark" >
                    <a href="{{ route('meeting.show', $meeting->id) }}" class="stretched-link text-white" ></a>
                    @if ($meeting['photoName'])
                        <img class="card-img-top" src="{{ url($meeting['photoName']) }}" alt="Foto de la asignatura">
                    @else
                        <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura">
                    @endif
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <p class="text-white">
                                {{ strlen($meeting->title)>34 ? substr($meeting->title, 0, 30).'...' : $meeting->title }}
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <p style="padding-bottom: 2px"> {{ $meeting->dateTime }} <p>
                            </div>
                            <div class="col-2">
                                <?php  $url = 'https://eu.bbcollab.com/collab/api/csa/sessions/'.$meeting->id.'/enrollments';
                                    $attendees = json_encode(Http::withToken(env('TOKEN'))->get($url)['results']);

                                ?>
                                <i class="fa fa-users users-icon" style="margin-right:0px" data-toggle="popover" data-trigger="hover" title="@lang('Attendees'):" data-content="{{ $attendees }}"></i>
                            </div>
                        </div>
                    </div>
                </div> 
            @empty
                <h4> Nothing to show yet.</h4>
            @endforelse
            </div>
        </div>
    </div>
</div>
@endsection