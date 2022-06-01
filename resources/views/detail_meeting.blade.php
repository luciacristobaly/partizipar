@extends('layout')

@section('title', 'Meeting')

@section('content')


<!-- list all the meetings -->
<div class="row"> 
    <h3> {{ $meeting['name'] }} </h3>
</div>
<div class="row">
    <p> Si quieres unirte a la reunión <a href="{{ $meeting['guestUrl'] }}" class="text-white">click aquí</a>
</div>
<div class="row">
    <h3> Participantes: </h3>
    <p>{{ $attendees }}</p>
</div>
@endsection