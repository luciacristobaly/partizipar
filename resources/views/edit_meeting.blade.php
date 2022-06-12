@extends('layout')

@section('title', 'Edit meeting')

@section('content')
<!-- edit meeting -->
<form class="container-fluid form-group" action="{{ route('meeting.update', [app()->getLocale(), $meeting['id']]) }}" method="PATCH" enctype="multipart/form-data" role="form">
    <div class="row">
        <div class="col-10"><h4> @lang('Edit meeting')</h4></div>
        <div class="col-2 font-weight-bold d-flex align-items-end">
            <a class="btn btn-md btn-danger text-white" href="javascript:;" data-toggle="modal" data-id="$meeting->id" data-target="#DeleteMeetingModal">
                @lang('DELETE')<i class="text-white fa fa-trash trash-icon pl-1"></i>
            </a>
        </div>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="title">@lang('Title')*:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="{{ $meeting['name'] }}" value="<?= $meeting['name'] ?>"/>
            {!! $errors->first('title', '<small>:message</small><br>') !!}
        </div>
        <div class="col">
            <label class=" control-label" for="manager">@lang('Manager')*:</label>
            <input type="text" id="manager" name="manager" class="form-control" placeholder="" value="{{ old('manager') }}"/>
            {!! $errors->first('manager', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="attendees_list">@lang('New attendees (split the list by semicolon)'):</label>
            <input type="text" id="attendee_email" name="attendee_email" class="form-control" placeholder="Email" value="{{ old('attendee_email') }}"/>
        </div>
        <div class="col">
            <label class="control-label" for="selectLecture">@lang('Lists'):</label>
            <select class="custom-select" name="lists" id="selectList" value="{{ old('selectList') }}">
                <option selected value="0"> Ninguna lista </option>
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
                <textarea class="ckeditor form-control" placeholder="{{ $meeting['body']}}" id="body" name="body"></textarea>    
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class="control-label" for="datetimestartpicker">@lang('Date time start')*:</label>
            <div class="input-group date"  id="datetimestartpicker">
                <input class="form-control" name="dateTimeStart" placeholder="{{$meeting['dateTime']}}" type="datetime" value="{{ old('dateTimeStart') }}"/>
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
                <option selected value="{{$meeting['lecture_id']}}"> Ningún curso </option>
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
            <a href="/home" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('CANCEL')</a>
            </div>
        </div>
        <div class="col">
        <div class="form-group text-center float-right">
                <button class="btn btn-outline-primary" name="submit" type="submit">@lang('ACCEPT')</button>
            </div>
        </div>
    </div>
</form>

<!-- Delete modal -->
<div class="modal fade" id="DeleteMeetingModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMeetingModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header dark-text">
            <input type=hidden id="id" name=id>
            <h4 id="DeleteMeetingModal" class="modal-title text-dark">@lang('WARNING!')</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        {{ csrf_field() }}
        <div class="modal-body text-dark">
            @lang('Are you sure you want to delete this meeting?')
        </div>
    
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <a type="button" class="btn btn-danger" href="{{ route('meeting.delete', [app()->getLocale(), $meeting->id]) }}">@lang('Yes')</a>
        </div>
    </div>
  </div>
</div>
 <!-- Modal -->

@endsection