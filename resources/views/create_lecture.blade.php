@extends('layout')

@section('title', 'New lecture')

@section('content')

<!-- create new lecture -->
<form class="container-fluid form-group" action="{{ route('lecture.store', app()->getLocale()) }}" method="POST" enctype="multipart/form-data" role="form">
    <div class="row">
        <h4>@lang('New lecture')</h4>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="title">@lang('Title')*:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="@lang('Title')" value="{{ old('title') }}"/>
            {!! $errors->first('title', '<small>:message</small><br>') !!}
        </div>
        <div class="col-3">
            <label class="control-label" for="image" >@lang('Image'):</label>
            <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}"/>
            {!! $errors->first('image', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="student_list">@lang('Students (split the list by semicolon)'):</label>
            <input type="text" id="student_email" name="student_email" class="form-control" placeholder="Email" value="{{ old('student_email') }}"/>
        </div>
        <div class="col">
            <label class="control-label" for="selectLecture">@lang('Lists'):</label>
            <select class="custom-select" aria-label="Default select example" name="lists" id="selectList" value="{{ old('selectList') }}">
                <option selected value="0"> Ninguna lista </option>
                @forelse($lists as $list)
                <option value="{{ $list['id'] }}">{{ $list['title'] }}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <div class="row form-group text-center float-left">
                <a href="{{ route('lectures', app()->getLocale()) }}" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('CANCEL')</a>
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