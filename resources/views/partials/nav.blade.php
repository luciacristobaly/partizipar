<!-- navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-primary" href="{{ route('home', app()->getLocale()) }}">
        <img src="..\..\images\logo.png" width="190" height="60" alt="PARTIZIPAR">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <ul class="navbar-nav">
            <li><a class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : ''}}" href="{{ route('home', app()->getLocale()) }}">@lang('Home')</a></li>
            <li><a class="nav-item nav-link {{ request()->routeIs('lectures') ? 'active' : '' }}{{ request()->routeIs('lecture.create') ? 'active' : '';}}  " href="{{ route('lectures', app()->getLocale()) }}">@lang('Lectures')</a></li>
            <li><a class="nav-item nav-link {{ request()->routeIs('meeting.create') ? 'active' : ''}}" href="{{ route('meeting.create', app()->getLocale()) }}">@lang('New meeting')</a></li>
            <li><a class="nav-item nav-link {{ request()->routeIs('lists') ? 'active' : ''}}" href="{{ route('lists', app()->getLocale()) }}">@lang('Lists')</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a class="nav-item nav-link {{ app()->getLocale()=='es' ? 'disabled' : ''}}" href="{{ str_replace('en', 'es', url()->full()) }}"><img src="..\..\images\es.png" width="20" height="20" alt="Spanish"></a></li>
            <li><a class="nav-item nav-link {{ app()->getLocale()=='en' ? 'disabled' : ''}}" href="{{ str_replace('es', 'en', url()->full()) }}"><img src="..\..\images\en.png" width="20" height="20" alt="English"></a></li>
        </ul>
    </div>
</nav>