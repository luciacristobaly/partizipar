@extends('layout')

@section('title', 'Lists')

@section('content')


<!-- Create new list -->
<div class="row">
    <form class="container-fluid form-group" action="{{route('lists', app()->getLocale())}}" method="POST">
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

<!-- Successfull update box -->
@if(\Session::has('success'))
<div class="alert alert-success">
    <p>{{ \Session::get('success') }}</p>
</div>
@endif
<!-- list all the lists -->
@if(count($lists)>0)
<table class="table table-dark table-hover" id="listsTable">
    <thead>
        <tr>
            <th>@lang('Click')</th>
            <th >@lang('Name of the list')</th>
            <th>@lang('Last update')</th>
            <th>@lang('Number of students')</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lists as $list)
        <tr>
            <th> <a href="{{ route('list.edit', [app()->getLocale(), $list->id]) }}" class="text-white"><i class="fa fa-arrow-circle-right arrow-circle-right-icon pr-1 text-white fa-lg"></i></a>
            <th> {{ $list->title }} </th>
            <td> {{ date_format(date_create($list['updated_at']), 'd/m/Y g:i A') }} </td>
            <td> {{ count(explode(",",$list['emails_list'])) }}</td>
            <td> 
                <a href="{{route('list.delete', [app()->getLocale(), $list->id])}}" class="btn"><i class="fa fa-trash trash-icon text-danger"></i></a>
            </td>
            <!--<td> 
                <button type="button" class="btn delete" data-toggle="modal" id="{{$list->id}}" data-target="#DeleteListModal" ><i class="text-danger fa fa-trash trash-icon pl-1 "></i></button>
            </td>-->
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="row text-center">
    <div class="col">
        <h4>@lang("There's nothing here yet... Let's create your first list!")</h4>
    </div>
</div>
@endif

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
        {{ csrf_field() }}
        <div class="modal-body text-dark">
            @lang('Are you sure you want to delete this list?')
            @lang('You will also delete the list from the meetings or lectures linked').
        </div>
    
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
            <a type="button" class="btn btn-default" href="">@lang('Yes')</a>
        </div>
    </div>
  </div>
</div>
 <!-- Modal -->

@endsection

@push('scripts')

<script type="text/javascript">

    $(document).ready(function () {
        var table = $(['#listsTable']).DataTable();

        //Start Delete Record
        table.on('click', '.delete', function() {

            $tr = $(this).closest('tr');
            if ($($tr).hasClass('child')) {
                $tr = $tr.prev('.parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#title').val(data[1]);
            #('#emails').val(data[2]);

            $('#deleteForm').attr('action', '/list/edit/'+data[0]);
            $('#DeleteListModal').modal('show');

        })
    });

@endpush