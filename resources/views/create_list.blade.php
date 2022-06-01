@extends('layout')

@section('title', 'Lists')

@section('content')

<form class="container-fluid form-group" action="/lists" method="POST">
    <div class="row">
        <h4>@lang('New lecture')</h4>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col">
            <label class="control-label" for="name">@lang('Name')*:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="@lang('Name')" value="{{ old('name') }}"/>
            {!! $errors->first('name', '<small>:message</small><br>') !!}
        </div>
        <div class="col">
            <label class="control-label" for="emails" >@lang('Attendees (split the list by semicolon)')*:</label>
            <input type="text" class="form-control" id="emails" name="emails" placeholder="email1@mail.com;email2@mail.com..." value="{{ old('emails') }}"/>
            {!! $errors->first('emails', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <div class="row form-group text-center float-left">
                <a href="/lists" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('CANCEL')</a>
            </div>
        </div>
        <div class="col">
        <div class="row form-group text-center float-right">
                <button class="btn btn-outline-primary" name="submit" type="submit">@lang('ACCEPT')</button>
            </div>
        </div>
    </div>
</form>  

@endsection