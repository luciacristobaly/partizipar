@extends('layout')

@section('title', 'List')

@section('content')


<form class="container-fluid form-group" action="{{ route('list.update', [app()->getLocale(), $list->id]) }}" method="PATCH" enctype="multipart/form-data" role="form">
@csrf
<div class="row"> 
    <div class="col">
        <a class="text-white" href="#new_name" data-toggle="collapse">
            <h3> {{ $list['title'] }}</h3>
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
            </tr>
        </thead>
        <tbody>
            @foreach($users as $email => $name)
                <?php $cont++; ?>
                <tr>
                    <th> {{ $cont }} </th>
                    <td> {{ $name }} </td>
                    <td> {{ $email }}</td>
                </tr>
            @endforeach
            <tr>
                <?php $cont++; ?>
                <th> {{ $cont }} </th>
                <td> <input type="text" class="form-control" placeholder="@lang('New student')" id="name" name="name"></textarea> </td>
                <td> <input type="text" class="form-control" placeholder="@lang('Email')" id="email" name="email"></textarea> </td>
                <td> <button type="submit" id="submit" name="submit" class="btn"><i class="fa fa-check-circle check-circle-icon text-success fa-2x"></i></button></td>
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
    <button onclick="history.back(-1)" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</button>
</div>

@endsection

@push('scripts')

<script>
$(document).ready(function () {
    $('#usersTable').DataTable();
});
</script>
@endpush