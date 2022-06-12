@extends('layout')

@section('title', 'Meeting')

@section('content')


<!-- list all the meetings -->
<div class="row"> 
    <div class="col"><h3> {{ $meeting['name'] }} <a href="{{ route('meeting.edit', [app()->getLocale(), $meeting['id']]) }}" id="edit" name="edit"><i class="fa fa-pencil pencil-icon pr-1 text-white"></i></a></h3></div>
</div>
<div class="row">
    <div class="col">
        <p> <a href="{{ $meeting['guestUrl'] }}">@lang('Click here')</a> @lang('to join the meeting'). </p>
    </div>
</div>
@if(count($attendees['results']) > 0)
    <?php  $cont = 0;?>
    <table class="table table-dark table-hover" id="attendeesTable">
        <thead>
            <tr>
                <th >@lang('Number')</th>
                <th>@lang('Name')</th>
                <th>@lang('Email')</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendees['results'] as $attendee)
                <?php   
                    $userId = $attendee['userId'];
                    $firstName = App\Models\User::where('id',$userId)->pluck('name')->first();
                    $displayName =  Http::withToken(env('TOKEN'))->get('https://eu.bbcollab.com/collab/api/csa/users/'.$userId);
                    $name = $firstName <> "Unknown" ? $firstName : $displayName['displayName']; 
                    $email = App\Models\User::where('id',$attendee['userId'])->pluck('email')->first();
                    $cont++;
                    ?>
                <tr>
                    <th> {{ $cont }} </th>
                    <td> {{ $name }} </td>
                    <td> {{ $email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="row text-center">
        <div class="col">
            <p> @lang("This meeting doesn't have attendees yet") </p>
        </div>
    </div>
@endif
<div class="footer">
    <button onclick="history.back(-1)" id="back" name="back" class="btn btn-outline-secondary">@lang('GO BACK')</button>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('#attendeesTable').DataTable();
});
</script>
@endpush