@extends('layout')

@section('title', 'Lectures')

@section('content')

<!-- create new lecture -->
<form class="container-fluid form-group" action="{{ route('lecture.update', [app()->getLocale(), $lecture['id']]) }}" method="PATCH">
<div class="row">
        <div class="col-10"><h4> @lang('Edit lecture')</h4></div>
        <div class="col-2 font-weight-bold d-flex align-items-end">
            <a class="btn btn-md btn-danger text-white" href="javascript:;" data-toggle="modal" data-id="$lecture" data-target="#DeleteLectureModal">
                @lang('DELETE')<i class="text-white fa fa-trash trash-icon pl-1"></i>
            </a>
        </div>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col-5">
            <label class=" control-label" for="title">@lang('Title')*:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="{{$lecture['title']}}" value="{{ old('title') }}"/>
            {!! $errors->first('title', '<small>:message</small><br>') !!}
        </div>
        <div class="col-3">
            <label class="control-label" for="image" >@lang('Image'):</label>
            <input type="file" class="form-control" id="image" name="image" value="{{ old('image') }}"/>
            {!! $errors->first('image', '<small>:message</small><br>') !!}
        </div>
        <div class="col-4">
            <label class=" control-label" for="professor">@lang('Professor')*:</label>
            <input type="text" id="professor" name="professor" class="form-control" placeholder="@lang('Professor')" value="{{ old('professor') }}"/> 
            {!! $errors->first('professor', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class=" control-label" for="student_list">@lang('New students (split the list by semicolon)'):</label>
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
            <button onclick="history.back(-1)" class="btn btn-outline-secondary">@lang('CANCEL')</button>
            </div>
        </div>
        <div class="col">
            <div class="row form-group text-center float-right">
                <button class="btn btn-outline-primary" name="submit" type="submit">@lang('ACCEPT')</button>
            </div>
        </div>
    </div>
</form>  

<!-- Delete modal -->
<div class="modal fade" id="DeleteLectureModal" tabindex="-1" role="dialog" aria-labelledby="DeleteLectureModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header dark-text">
            <input type=hidden id="id" name=id>
            <h4 id="DeleteLectureModal" class="modal-title text-dark">@lang('WARNING!')</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        {{ csrf_field() }}
        <div class="modal-body text-dark">
            @lang('Are you sure you want to delete this lecture?')
            @lang('You will also delete all meetings of this lecture').
        </div>
    
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <a type="button" class="btn btn-danger" href="{{ route('lecture.delete', [app()->getLocale(), $lecture->id]) }}">@lang('Yes')</a>
        </div>
    </div>
  </div>
</div>
 <!-- Modal -->
@endsection