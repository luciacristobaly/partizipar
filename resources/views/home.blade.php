@extends('layout')

@section('title', 'Home')

@section('content')


<!-- list all the meetings -->
<h3>@lang('Next meetings of '){{ $userName }}</h3>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
            @forelse ($meetings as $meeting)
            @csrf
                <div class="card mb-3 bg-dark" >
                    <a href="{{ route('meeting.show', [app()->getLocale(), $meeting->id]) }}" class="stretched-link text-white" ></a>
                    @if ($meeting['photoName'])
                        <img class="card-img-top" src="{{ url($meeting['photoName']) }}" alt="Foto de la asignatura">
                    @else
                        <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura">
                    @endif
                    <?php
                        $name = strlen($meeting->title)>34 ? substr($meeting->title, 0, 30) : $meeting->title;
                        $date = date_format(date_create($meeting->dateTime), 'g:i A');
                        $texto = $date . ' - ' . $name;
                        ?>
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <p>
                                {{ $texto }}
                            </p>
                        </div>
                    </div>
                </div> 
            @empty
                <h4> @lang("You don't have any meeting programmed").</h4>
            @endforelse
            </div>
        </div>
    </div>
</div>
@endsection