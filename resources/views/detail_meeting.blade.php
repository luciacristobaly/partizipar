@extends('layout')

@section('title', 'Meeting')

@section('content')


<!-- list all the meetings -->
<div class="row"> 
    <div class="col"><h3> {{ $meeting['name'] }} </h3></div>
    <div class="col d-flex align-items-end"><a href="{{ route('meeting.edit', $meeting['id']) }}" id="edit" name="edit"><i class="fa fa-pencil pencil-icon pr-1 text-white"></i></a></div>
</div>
<div class="row">
    <p> Si quieres unirte a la reunión <a href="{{ $meeting['guestUrl'] }}" class="text-white">click aquí</a>
</div>
<div class="row">
    <h3> @lang('Attendees'): </h3>
</div>
<?php  $cont = 0;?>
@forelse($attendees['results'] as $attendee)
<?php   $name = App\Models\User::where('id',$attendee['userId'])->pluck('name')->first(); 
        $cont++;?>
<div class="row">
    <p> {{ $cont.'- '.$name }} </p>
</div>
@empty
<div class="row">
    <p> @lang("This meeting doesn't have attendees yet") </p>
</div>
@endforelse

<div class="footer">
    <button onclick="history.back(-1)" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</button>
</div>

@endsection