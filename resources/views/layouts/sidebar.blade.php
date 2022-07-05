<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            @if (\Auth::user()->profile_photo_path != '')
                <img src="{{ \Auth::user()->profile_photo_path }}" class="rounded-circle user-photo"
                    alt="User Profile Picture">
            @endif
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name"
                    data-toggle="dropdown"><strong>{{ isset(\Auth::user()->name) ? \Auth::user()->name : '' }}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="{{ route('profile') }}"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="{{ route('setting') }}"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>
            <hr>
        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu"><i
                        class="fa fa-database"></i> Data</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">
                        @if (\Auth::user()->user_access_id == 1)
                            <!--Administrator-->
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="/"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'log-activity' ? 'active' : null }}">
                                <a href="{{ route('log-activity') }}"><i class="fa fa-history"></i> <span>Log
                                        Activity</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 2)
                            <!--Finance-->
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="/"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 3)
                            <li class="{{ Request::segment(1) === 'polis' ? 'active' : null }}">
                                <a href="{{ route('polis.index') }}"><i class="fa fa-home"></i>
                                    <span>Pemegang Polis</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 4)
                            <li class="{{ Request::segment(1) === 'polis' ? 'active' : null }}">
                                <a href="{{ route('polis.index') }}"><i class="fa fa-home"></i>
                                    <span>Pemegang Polis</span></a>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 5)
                            <!--Teknis-->
                            <li
                                class="{{ Request::segment(1) === 'konven-underwriting' || Request::segment(1) === 'konven-reinsurance' || Request::segment(1) === 'konven-claim' ? 'active' : null }}">
                                <a href="#App" class="has-arrow"><i class="fa fa-database"></i> <span>Konven</span></a>
                                <ul>
                                    <li class="{{ Request::segment(1) === 'konven-underwriting' ? 'active' : null }}">
                                        <a href="{{ route('konven.underwriting') }}">Underwriting</a>
                                    </li>
                                    <li class="{{ Request::segment(1) === 'konven-reinsurance' ? 'active' : null }}">
                                        <a href="{{ route('konven.reinsurance') }}">Reinsurance</a>
                                    </li>
                                    {{-- <li class="{{ Request::segment(1) === 'konven-claim' ? 'active' : null }}"><a href="{{route('konven.claim')}}">Claim</a></li> --}}
                                </ul>
                            </li>
                            <li
                                class="{{ Request::segment(1) === 'syariah-underwriting' || Request::segment(1) === 'syariah-reinsurance' ? 'active' : null }}">
                                <a href="#App" class="has-arrow"><i class="fa fa-database"></i> <span>Syariah</span></a>
                                <ul>
                                    <li
                                        class="{{ Request::segment(1) === 'syariah-underwriting' ? 'active' : null }}">
                                        <a href="{{ route('syariah.underwriting') }}">Underwriting</a>
                                    </li>
                                    <li
                                        class="{{ Request::segment(1) === 'syariah-reinsurance' ? 'active' : null }}">
                                        <a href="{{ route('syariah.reinsurance') }}">Reinsurance</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
