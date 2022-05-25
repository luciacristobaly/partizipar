@extends('layout')

@section('title', 'Lectures')

@section('content')
<div class="row">
    <a href="{{ route('lecture.create') }}" role="button" class="btn btn-outline-primary bg-white text-dark font-weight-bold"> NUEVO CURSO </a> 
</div>

<!-- list all the lectures -->
<div class="row pt-5">
    <div class="container-fluid" width="80%">
        <div class="row flex-row flex-nowrap">
            <!-- list all the lectures -->
            <div class="row pt-5">
                <div class="container-fluid" width="80%">
                    <div class="row flex-row flex-nowrap">
                        @forelse ($lectures as $lecture)
                        <div class="col-sm-3">
                            <div class="card card-block zoom">
                                @csrf
                                <img class="card-img-top" src="https://www.arqhys.com/general/wp-content/uploads/2011/07/Roles-de-la-inform%C3%A1tica.jpg" alt="Foto de la asignatura">
                                <div class="card-body bg-dark">
                                    <h5 class="card-title">{{ $lecture['name'] }}</h5>
                                </div>
                            </div> 
                        </div>
                        @empty
                            <h4>No estás matriculado en ningún curso.</h4>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection