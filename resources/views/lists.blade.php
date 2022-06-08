@extends('layout')

@section('title', 'Lists')

@section('content')


<!-- Create new list -->
<div class="row">
    <form class="container-fluid form-group" action="/lists" method="POST">
        @csrf
        <div class="row">
            <div class="col-4">
                <label class="control-label" for="name">@lang('Name')*:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="@lang('Name')" value="{{ old('name') }}"/>
                {!! $errors->first('name', '<small>:message</small><br>') !!}
            </div>
            <div class="col-7">
                <label class="control-label" for="emails" >@lang('Attendees (split the list by semicolon)')*:</label>
                <input type="text" class="form-control" id="emails" name="emails" placeholder="email1@mail.com; email2@mail.com..." value="{{ old('emails') }}"/>
                {!! $errors->first('emails', '<small>:message</small><br>') !!}
            </div>
            <div class="col-1">
                <div class="accept-list-button form-group">
                    <div class="center">
                        <button class="btn btn-outline-primary " style="font-size: 15px" name="submit" type="submit">@lang('ACCEPT')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- list all the lists -->
<div class="row">
    <h3>@lang('Available lists')</h3>
</div>
<table class="table table-dark table-hover display" id="listsTable">
    <thead>
        <tr class="d-flex">
            <th class="col-8">@lang('Name of the list')</th>
            <th class="col-2 text-center">@lang('Number of students')</th>
            <th class="col-2 text-center">@lang('Actions')</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($lists as $list)
        <tr class="d-flex">
            <td class="col-8"> {{ $list['title'] }} </td>
            <td class="col-2 text-center"> {{ count(explode(",",$list['emails_list'])) }}</td>
            <td class="col-2 text-center"> 
                <a class="btn"  href="{{ route('list.edit', $list->id) }}"><i class="fa fa-pencil pencil-icon pr-1 text-white"></i></a>
                <a href="javascript:;" data-toggle="modal" data-id="$list->id" data-target="#DeleteListModal"><i class="text-danger fa fa-trash trash-icon pl-1"></i> </a>
            </td>
        </tr>
        @empty
            <h4>No tienes listas.</h4>
        @endforelse
    </tbody>
</table>

<!-- List users modal -->
<div class="modal fade" id="showList" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <input type=hidden id="id" name=id>
                <h4 id="showList" class="modal-title text-dark">@lang('List details'):</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-dark">
                <div class="listname"><p>@lang('List name'): </p><span></span></div>
                <div class="students"><p>@lang('Students'): </p><span></span></div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div class="modal fade" id="DeleteListModal" tabindex="-1" role="dialog" aria-labelledby="DeleteListModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header dark-text">
            <input type=hidden id="id" name=id>
            <h4 id="DeleteListModal" class="modal-title text-dark">@lang('WARNING!')</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <form action="{{ route('list.delete', $list->id) }}" method="post">
        {{ csrf_field() }}
            <div class="modal-body text-dark">
                @lang('Are you sure you want to delete the selected list?')
            </div>
        
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button class="btn btn-default" type=submit>@lang('Yes')</button>
            </div>
        </form>
    </div>
  </div>
</div>
 <!-- Modal -->

@endsection

<script>
    jQuery(document).ready(function($) {
    $('#listsTable').DataTable({
        searching: false,
        responsive: true,
    });
    var table = $('#listsTable').DataTable();
    $('#listsTable tbody').on('click', 'tr', function () {
        //console.log(table.row(this).data());
        $(".listname span").text(table.row(this).data()[0]);
        $(".students span").text(table.row(this).data()[1]);
        $("#showList").modal("show");
    });
} );
</script>