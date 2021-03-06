<nav class="navbar navbar-pos navbar-expand-md navbar-laravel p-1 shadow fixed-top">
    <div class="collapse navbar-collapse col-md-2 px-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button> 
    </div>
    <div class="col-md-8 text-center" style="color:white;">
        <!-- Left Side Of Navbar -->
        {{-- <span class="px-5 mx-5" id="currentDatetime" style="color:white;"></span> --}}
        {{-- <span id="date"></span>
        <span id="time" class="pl-1"></span> --}}
    </div>
    <div class="collapse navbar-collapse col-md-2 px-1">
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="px-1 caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" style="color:#505050 !important;" href="/shiftEnd">
                           Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row sidebarMainContent">
        <div class="container col-md-2" style="margin:0; padding:0;">
            <nav class="d-none d-md-block sidebar sidebar-pos">
                <ul class="nav flex-column nav-list">
                    <li class="nav-item">
                        <a class="nav-link" href="/create-order" id="POSDashboard">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="dropdown-btn nav-link-drop nav-link-drop-pos" id="restoTransactionsTab">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <span> Transactions </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container" id="dropdownRestoTransactions">
                            <a class="dropdown-item" href="view-orders">
                                <i class="fa fa-file-invoice" aria-hidden="true"></i>
                                <span> Orders </span>
                            </a>
                            <a class="dropdown-item" href="view-restaurant-payments">
                                <i class="fa fa-receipt" aria-hidden="true"></i>
                                <span> Payments </span>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <button class="dropdown-btn nav-link-drop nav-link-drop-pos" id="restoReportsTab">
                            <i class="fa fa-print" aria-hidden="true"></i>
                            <span> Reports </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container" id="dropdownRestoReports">
                            <a class="dropdown-item" href="/cashier-shift-report">
                                <i class="fa fa-cash-register" aria-hidden="true"></i>
                                <span> Shift Reports </span>
                            </a>
                            <a class="dropdown-item" href="/todays-restaurant-report" id="restaurantReports">
                                <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                <span> Sales Reports </span>
                            </a>
                        </div>
                    </li>
                </ul>
                <div class="text-center mb-4 px-4" style="background-color:#0808084a; color: white; position:fixed; bottom:0; width:17%;">
                    <span id="week"></span>
                    <span id="date" class="pl-1"></span>
                    <span id="time" class="pl-1"></span>
                </div>
            </nav>
        </div>