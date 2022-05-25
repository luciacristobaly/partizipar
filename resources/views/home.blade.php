@extends('layout')

@section('title', 'Home')

@section('content')


<!-- list all the meetings -->
<h3>@lang('Following meetings of '){{ $userName }}</h3>
<div class="row container-fluid">
    <div class="row flex-row flex-nowrap position-relative">
        @forelse ($meetings as $meeting)
        <div class="col-sm-3">
            <div class="card card-block zoom">
            @csrf
                @if ($meeting['photoName'])
                    <img class="card-img-top" src="{{ url($meeting['photoName']) }}" alt="Foto de la asignatura">
                @else
                    <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura">
                @endif
                <div class="card-body bg-dark">
                    <a href="{{ route('meeting.show', $meeting->id) }}" class="stretched-link text-white">{{ $meeting->title }}</a>
                </div>
            </div> 
        </div>
        @empty
            <h4>No tienes reuniones programadas.</h4>
        @endforelse
        </div>
    </div>
</div>
@endsection