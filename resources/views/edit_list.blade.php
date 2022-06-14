@extends('layout')

@section('title', 'List')

@section('content')


<form class="container-fluid form-group" action="{{ route('list.update', [app()->getLocale(), $list->id, 'edit']) }}" method="PATCH" enctype="multipart/form-data" role="form">
@csrf
<div class="row"> 
    <div class="col">
        <a class="text-white" href="#new_name" data-toggle="collapse">
            <h3> {{ $list['title'] }} <i class="fa fa-pencil pencil-icon pr-1 text-white"></i></h3>
        </a>
            <div class="collapse p-3" id="new_name">
                <div class="row">
                    <div class="col-4">
                        <input type="text" class="form-control" placeholder="@lang('New title')" id="title" name="title"></textarea>    
                    </div>
                    <div class="col-1">
                        <button type="submit" id="submit" name="submit" class="btn btn-secondary">OK</button>
                    </div>
                </div>
            </div>
    </div>
</div>
{!! $errors->first('email', '<div class="alert alert-danger"><p>No es un email válido</p></div>') !!}
@if(count($users) > 0)
    <?php  $cont = 0;?>
    <table class="table table-dark table-hover" id="usersTable">
        <thead>
            <tr>
                <th >@lang('Number')</th>
                <th>@lang('Name')</th>
                <th>@lang('Email')</th>
                <th>@lang('Actions')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $email => $name)
                <?php $cont++; ?>
                <tr>
                    <th> {{ $cont }} </th>
                    <td> {{ $name }} </td>
                    <td> {{ $email }}</td>
                    <td> 
                        <a href="{{route('list.update', [app()->getLocale(), $list->id, $email])}}" class="btn"><i class="fa fa-trash trash-icon text-danger"></i></a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <?php $cont++; ?>
                <th> {{ $cont }} </th>
                <td> <input type="text" class="form-control" placeholder="@lang('New student')" id="name" name="name"></textarea> </td>
                <td> <input type="text" class="form-control" placeholder="@lang('Email')" id="email" name="email"></textarea> </td>
                <td> <button type="submit" id="submit" name="submit" class="btn"><i class="fa fa-check-circle check-circle-icon text-success"></i></button></td>
            </tr>
        </tbody>
    </table>
    
@else
    <div class="row text-center">
        <div class="col">
            <p> @lang("This list is empty"). </p>
        </div>
    </div>
@endif
<div class="footer">
    <a href="{{ route('lists', [app()->getLocale()]) }}" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</a>
</div>

@endsection

@push('scripts')

<script>
$(document).ready(function () {
    $('#usersTable').DataTable();
});
</script>
@endpush