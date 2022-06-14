@extends('layout')

@section('title', 'Meeting')

@section('content')


<!-- list all the meetings -->
<form class="container-fluid form-group" action="{{ route('meeting.update', [app()->getLocale(), $meeting['id'], 'edit']) }}" method="PATCH" enctype="multipart/form-data" role="form">
<div class="row">
    <?php 
        date_default_timezone_set('Europe/Madrid') ;
        $currentTime = date('m/d/Y h:i:s a', time());
    ?>
    <div class="col">
        @if($meeting['startTime'] > $currentTime || $meeting['endTime'] < $currentTime)
        <a class="text-white" href="#edit" data-toggle="collapse">
            <h3> {{ $meeting['name'] }} <i class="fa fa-pencil pencil-icon pr-1 text-white"></i></h3>
            @if($lecture <> null)
            <p>@lang('owns by the lecture') <a class="font-weight-bold" href="{{ route('lecture.show', [app()->getLocale(), $lecture->id]) }}">{{$lecture->title}} </a></p>
            @endif
        </a>
        @endif
    </div> 
</div>
<div class="row">
    <div class="col">
        @if($meeting['startTime'] <= $currentTime && $meeting['endTime'] >= $currentTime)
        <p> <a href="{{ $meeting['guestUrl'] }}">@lang('Click here')</a> @lang('to join the meeting'). </p>
        @else
        <p> @lang('Date and time of the meeting'): {{ date_format(date_create($meeting['startTime']), "d/m/Y H:i") }} </p>
        @endif
        <div class="collapse p-3" id="edit">
            <div class="row">
                <div class="col-3">
                    <input type="text" class="form-control" placeholder="@lang('New title')" id="title" name="title"></textarea>    
                </div>
                <div class="col-2">
                    <select class="custom-select" aria-label="Default select example" name="lectureOwner" id="selectLecture">
                        <option selected value="0"> @lang('Any lecture')</option>
                        @forelse($list_lectures as $l)
                        <option value="{{ $l['id'] }}">{{ $l['title'] }}</option>
                        @empty
                        @endforelse
                    </select>   
                </div>
                <div class="col-2">
                    <select class="custom-select" name="list_id" id="selectList">
                        <option selected value="0"> @lang('Any list') </option>
                        @forelse($list_users as $users)
                        <option value="{{ $users['id'] }}">{{ $users['title'] }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-2">
                    <div class="input-group date"  id="datetimestartpicker">
                        <input class="form-control" name="dateTimeStart" placeholder="@lang('Start')" type="datetime"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="col-2">
                    <div class="input-group date"  id="datetimestartpicker">
                        <input class="form-control" name="dateTimeEnd" placeholder="@lang('End')" type="datetime"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="col-1">
                    <button type="submit" id="submit" name="submit" class="btn btn-secondary">OK</button>
                </div>
            </div>
        </div>
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
                    <th>@lang('Actions')</th>
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
                        <td> 
                            <a href="{{route('meeting.update', [app()->getLocale(), $meeting['id'], $attendee['id']])}}" class="btn"><i class="fa fa-trash trash-icon text-danger"></i></a>
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
                <p> @lang("This meeting doesn't have attendees yet") </p>
            </div>
        </div>
    @endif
    <div class="footer">
    <a href="{{ route('home', [app()->getLocale()]) }}" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('GO BACK')</a>
    </div>
</form>

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