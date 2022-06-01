@extends('layout')

@section('title', 'Lecture')

@section('content')


<!-- list all the meetings -->
<div class="row"> 
    <h3> {{ $lecture['title'] }} </h3>
</div>
<div class="row">
    <h3> Reuniones: </h3>
</div>
<div class="row">
    @forelse ($meetings as $meeting)
        <h4>{{ $meeting }}</h4>
    @empty
        <h4>@lang("This lecture doesn't have any meeting.")
    @endforelse
</div>
@endsection