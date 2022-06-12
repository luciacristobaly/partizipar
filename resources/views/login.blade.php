<!-- navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-primary" href="#">
        <img src="..\..\images\logo.png" width="190" height="60" alt="PARTIZIPAR">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <ul class="nav navbar-nav navbar-right">
            <li><a class="nav-item nav-link {{ app()->getLocale()=='es' ? 'disabled' : ''}}" href="{{ str_replace('en', 'es', url()->full()) }}"><img src="..\..\images\es.png" width="20" height="20" alt="Spanish"></a></li>
            <li><a class="nav-item nav-link {{ app()->getLocale()=='en' ? 'disabled' : ''}}" href="{{ str_replace('es', 'en', url()->full()) }}"><img src="..\..\images\en.png" width="20" height="20" alt="English"></a></li>
        </ul>
    </div>
</nav>
<div class="container-fluid mh-100 mw-100">
    <div class="row text-center">
        <div class="col text-center">
            <a href="{{ route('login',app()->getLocale()) }}" >
                <button class="btn btn-primary text-white" id="login" name="login" on>@lang('Login')</button>
            </a>
        </div>
    </div>
</div>