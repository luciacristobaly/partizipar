@extends('layout')

@section('title', 'Lectures')

@section('content')

<div class="row">
    <div class="col-2">
        <h3>@lang('Lectures')</h3>
    </div>
    <div class="col-8" style="margin-top: 15px">
        <a href="{{ route('lecture.create') }}" role="button" class="btn btn-outline-primary bg-white text-dark font-weight-bold"> NUEVO CURSO </a> 
    </div>
</div>


<!-- list all the lectures -->
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card-deck">
            @forelse ($lectures as $lecture)
            @csrf
                <div class="card mb-3 bg-dark">
                    <a href="{{ route('lecture.show', $lecture->id) }}" class="stretched-link text-white"></a>
                    @if ($lecture['photoName'])
                        <img class="card-img-top" src="{{ url($lecture['photoName']) }}" alt="Foto de la asignatura">
                    @else
                        <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura">
                    @endif
                    <div class="row content">
                        <div class="row" style="margin-left:0px">
                            <h4> {{ strlen($lecture->title)>24 ? substr($lecture->title, 0, 20).'...' : $lecture->title }} </h4>
                        </div>
                    </div>
                </div> 
            @empty
                <h4> Nothing to show yet.</h4>
            @endforelse
            </div>
        </div>
    </div>
</div>


@endsection