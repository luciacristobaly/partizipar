@extends('layout')

@section('title', 'Lectures')

@section('content')

<!-- create new lecture -->
<form class="container-fluid form-group" action="/lectures" method="POST">
    <div class="row">
        <h4>@lang('New lecture')</h4>
    </div>
    @csrf
    <div class="row p-3">
        <div class="col-5">
            <label class=" control-label" for="title">@lang('Title')*:</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="@lang('Title')" value="{{ old('title') }}"/>
            {!! $errors->first('title', '<small>:message</small><br>') !!}
        </div>
        <div class="col-2">
            <label class="control-label" for="photoPathGroup" >@lang('Image'):</label>
            <div class="custom-file" id="photoPathGroup">
                <input type="file" class="custom-file-input" id="photoName" lang="es" name="photoName" value="{{ old('photoName') }}"/>
                <label class="custom-file-label" for="photoName" >@lang('Upload')</label>
            </div>
        </div>
        <div class="col-5">
            <label class=" control-label" for="professor">@lang('Professor')*:</label>
            <input type="text" id="professor" name="professor" class="form-control" placeholder="@lang('Professor')" value="{{ old('professor') }}"/> 
            {!! $errors->first('professor', '<small>:message</small><br>') !!}
        </div>
    </div>
    <div class="row p-3">
        <div class="col-6">
            <label class=" control-label" for="students">@lang('Students'):</label>
            <table id="students" class="table">
                <tr>
                    {{ $num_student = 0 }}
                    <td>
                        <input type="email" id="{{ 'student_'.$num_student.'_email' }}" name="{{ 'student_'.$num_student.'_email' }}" class="form-control name_list" placeholder="Email"/>
                    </td>
                    <td> 
                        <button name="add" id="add" class="btn btn-success">@lang('Add More')</button>
                        {{ $num_student = $num_student + 1 }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <label class="control-label" for="datetimestartpicker">@lang('Date time start'):</label>
            <div class="input-group date"  id="datetimestartpicker">
                <input class="form-control" name="dateTimeStart" placeholder="MM/DD/AAAA hh:mm:ss" type="text" value="{{ old('dateTimeStart') }}"/> 
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
        <div class="col">
            <label class="control-label" for="datetimeendpicker">@lang('Date time end'):</label>
            <div class="input-group date"  id="datetimeendpicker">
                <input class="form-control" name="dateTimeEnd" placeholder="MM/DD/AAAA hh:mm:ss" type="text" value="{{ old('dateTimeEnd') }}"/> 
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="row p-3">
        <div class="col">
            <div class="row form-group text-center float-left">
                <a href="/lectures" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('CANCEL')</a>
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