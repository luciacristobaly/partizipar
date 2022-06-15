@extends('layout')

@section('title', 'New meeting')

@section('content')
<!-- create new meeting -->
<form class="container-fluid form-group" action="{{ route('meeting.store', app()->getLocale()) }}" method="POST" enctype="multipart/form-data" role="form">
    <div class="row">
        <h4> @lang('New meeting')</h4>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="title">@lang('Title')*:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="@lang('Title')" value="{{ old('title') }}"/>
            {!! $errors->first('title', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="attendees_list">@lang('Attendees (split the list by semicolon)'):</label>
            <input type="text" id="attendee_email" name="attendee_email" class="form-control" placeholder="Email" value="{{ old('attendee_email') }}"/>
        </div>
        <div class="col">
            <label class="control-label" for="selectLecture">@lang('Lists'):</label>
            <select class="custom-select" name="lists" id="selectList" value="{{ old('selectList') }}">
                <option selected value="0"> @lang('Any list') </option>
                @forelse($lists as $list)
                <option value="{{ $list['id'] }}">{{ $list['title'] }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>
    <div class="row p-3">
        <div class="col container">
            <a class="btn btn-default" href="#body_box" data-toggle="collapse">@lang('Write email')</a>
            <div class="collapse p-3" id="body_box">
                <textarea class="ckeditor form-control" placeholder="@lang('Write email')" id="body" name="body"></textarea>    
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class="control-label" for="datetimestartpicker">@lang('Date time start')*:</label>
            <div class="input-group date"  id="datetimestartpicker">
                <input class="form-control" name="dateTimeStart" placeholder="@lang('MM/DD/YYYY hh:mm:ss')" type="datetime" value="{{ old('dateTimeStart') }}"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            {!! $errors->first('dateTimeStart', '<small>:message</small><br>') !!}
        </div>
        <div class="col">
            <label class="control-label" for="datetimeendpicker">@lang('Date time end')*:</label>
            <div class="input-group date"  id="datetimeendpicker">
                <input class="form-control" name="dateTimeEnd" placeholder="@lang('MM/DD/YYYY hh:mm:ss')" type="datetime" value="{{ old('dateTimeEnd') }}"/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            {!! $errors->first('dateTimeEnd', '<small>:message</small><br>') !!}
        </div>
        <div class="col">
            <label class="control-label" for="selectLecture">@lang('Lecture'):</label>
            <select class="custom-select" aria-label="Default select example" name="lectureOwner" id="selectLecture" value="{{ old('selectLecture') }}">
                <option selected value="0"> @lang('Any lecture')</option>
                @forelse($lectures as $lecture)
                <option value="{{ $lecture['id'] }}">{{ $lecture['title'] }}</option>
                @empty
                @endforelse
            </select>
        </div>
        <div class="col">
            <label class="control-label" for="image" >@lang('Image'):</label>
            <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}"/>
            {!! $errors->first('image', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <div class="form-group text-center float-left">
            <a href="{{ route('home', app()->getLocale()) }}" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('CANCEL')</a>
            </div>
        </div>
        <div class="col">
            <div class="form-group text-center float-right">
                <button class="btn btn-outline-primary" name="submit" type="submit">@lang('ACCEPT')</button>
            </div>
        </div>
    </div>
</form>


@endsection