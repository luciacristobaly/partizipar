@extends('layout')

@section('title', 'Lecture')

@section('content')


<!-- list all the meetings -->
<form class="container-fluid form-group" action="{{ route('lecture.update', [app()->getLocale(), $lecture['id'], 'edit']) }}" method="PATCH" enctype="multipart/form-data" role="form">
<div class="row">
    <div class="col">
        <a class="text-white" href="#edit" data-toggle="collapse">
            <h3> {{ $lecture->title }} <i class="fa fa-pencil pencil-icon pr-1 text-white"></i></h3>
        </a>
    </div> 
</div>
<div class="row">
    <div class="col">
        <div class="collapse p-3" id="edit">
            <div class="row">
                <div class="col-5">
                    <input type="text" class="form-control" placeholder="@lang('New title')" id="title" name="title"></textarea>    
                </div>
                <div class="col-3">
                    <select class="custom-select" name="list_id" id="selectList">
                        <option selected value="0"> @lang('Any list') </option>
                        @forelse($list_users as $users)
                        <option value="{{ $users['id'] }}">{{ $users['title'] }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-1">
                    <button type="submit" id="submit" name="submit" class="btn btn-secondary">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <h4> @lang('Meetings'): </h4>
    </div>
</div>
@if(count($meetings)>0)
<div class="row container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
                @foreach ($meetings as $meeting_id => $meeting_photo)
                @csrf
                <div class="card mb-3 bg-dark">
                    <a href="{{ route('meeting.show', [app()->getLocale(), $meeting_id]) }}" class="stretched-link text-white"></a>
                    @if ($meeting_photo <> null)
                    <img class="card-img-top" src="{{ url($meeting_photo) }}" alt="Foto de la reuni??n"/>
                    @else
                    <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura"/>
                    @endif
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <p class="text-white">
                                <?php 
                                    $response = Http::withToken(env('TOKEN'))->get('https://eu.bbcollab.com/collab/api/csa/sessions/'.$meeting_id); 
                                    echo strlen($response['name'])>34 ? substr($response['name'], 0, 30).'...' : $response['name'];
                                    ?>
                            </p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p style="padding-bottom: 2px">{{ $response['startTime'] }}</p>
                            </div>
                        </div>
                    </div>
                </div> 
                @endforeach
            </div>
        </div>
    </div>
</div>
@else
<div class="row text-center">
    <div class="col">
        <p> @lang("This lecture doesn't have any meeting"). </p>
    </div>
</div>
@endif
<div class="footer">
    <div class="row">
        <div class="col">
            <a href="{{ route('lectures', [app()->getLocale()]) }}" id="cancel" name="cancel" class="btn btn-outline-secondary">@lang('GO BACK')</a>
        </div>
        <div class="col text-center float-right">
            <a class="btn btn-md btn-danger text-white" href="javascript:;" data-toggle="modal" data-id="$lecture" data-target="#DeleteLectureModal">
                @lang('DELETE')<i class="text-white fa fa-trash trash-icon pl-1"></i>
            </a>
        </div>
    </div>
</div>

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