@extends('layout')

@section('title', 'Lists')

@section('content')
<div class="row">
    <h3>@lang('Lists')</h3>
</div>

<!-- list all the lists -->
@forelse ($lists as $list)
<div class="row">
    <div class="col container">
        <a class="text-white" href="#{{'id-'.(string)$list['id']}}" data-toggle="collapse">
            <h4>{{ $list['title'] }}</h4>
        </a>
        <div class="collapse p-3" id="{{'id-'.(string)$list['id']}}">
            {{ $list['emails_list'] }}    
        </div>
    </div>
</div>
@empty
    <h4>No tienes listas.</h4>
@endforelse

<div class="row">
    <a href="{{ route('list.create') }}" role="button" class="btn btn-outline-primary bg-white text-dark font-weight-bold"> NUEVA LISTA </a> 
</div>


@endsection