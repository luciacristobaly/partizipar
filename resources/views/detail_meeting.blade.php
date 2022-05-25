@extends('layout')

@section('title', 'Meeting')

@section('content')


<!-- list all the meetings -->
<h3> {{ $meeting['name'] }} </h3>
<p> Si quieres unirte a la reunión <a href="{{ $meeting['guestUrl'] }}" class="text-white">click aquí</a>

@endsection