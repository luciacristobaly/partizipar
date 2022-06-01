<!-- navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-primary" href="#">
        <img src="..\..\images\logo.png" width="180" height="50" alt="PARTIZIPAR">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : ''}}" href="/home">@lang('Home')</a>
            <a class="nav-item nav-link {{ request()->routeIs('lectures') ? 'active' : '' }}{{ request()->routeIs('lecture.create') ? 'active' : '';}}  " href="/lectures">@lang('Lectures')</a>
            <a class="nav-item nav-link {{ request()->routeIs('meeting.create') ? 'active' : ''}}" href="{{ route('meeting.create') }}">@lang('New meeting')</a>
            <a class="nav-item nav-link {{ request()->routeIs('lists') ? 'active' : ''}}" href="{{ route('lists') }}">@lang('Lists')</a>
        </div>
    </div>
</nav>