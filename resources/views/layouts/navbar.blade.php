<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
        </div>
        <div class="navbar-brand">
            @if (isset($logo))
                <a href="/"><img src="{{ $logo }}" class="img-responsive logo"></a>
            @endif
        </div>
        <div class="navbar-right">
            <form id="navbar-search" class="navbar-form search-form col-md-9">
                <div id="navbar-menu float-left">
                    <ul class="nav navbar-nav">
                        {{-- Administrator --}}
                        @if (\Auth::user()->user_access_id == 1)
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Data Master</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('extra-mortalita.index') }}">Extra Mortalita</a></li>
                                    {{-- <li><a href="{{ route('uw-limit.index') }}">UW Limit</a></li> --}}
                                    <li><a href="{{ route('produk.index') }}">Produk</a></li>
                                    {{-- <li><a href="{{ route('rate.index') }}">Rate</a></li> --}}
                                    <li><a href="{{ route('reasuradur.index') }}">Reasuradur</a></li>
                                    <li><a href="{{ route('users.index') }}">Users</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('polis.index') }}" class="text-info icon-menu px-1">Pemegang Polis</a>
                            </li>
                            <li><a href="{{ route('pengajuan.index') }}" class="text-info icon-menu px-1">Pengajuan</a></li>
                        @endif
                        <!--Finance-->
                        @if (\Auth::user()->user_access_id == 2)
                            <li><a href="{{ route('polis.index') }}" class="text-info">Pemegang Polis</a></li>
                        @endif
                        @if (\Auth::user()->user_access_id == 3)
                            <li><a href="{{ route('polis.index') }}" class="text-info px-1 icon-menu" class="text-info icon-menu px-1"> Pemegang Polis </a></li>
                            <li><a href="{{ route('pengajuan.index') }}" class="text-info icon-menu px-1">Pengajuan</a></li>
                        @endif
                        @if (\Auth::user()->user_access_id == 4)
                            <li  class="text-info px-1 icon-menu"><a href="{{ route('polis.index') }}" class="text-info icon-menu px-1">Pemegang Polis</a></li>
                            <li><a href="{{ route('pengajuan.index') }}" class="text-info icon-menu px-1">Pengajuan</a></li>
                        @endif
                        @if (\Auth::user()->user_access_id == 5)
                            <!--Teknis-->
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Konven</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('teknis.konven.underwriting') }}">Underwriting</a></li>
                                    <li><a href="{{ route('teknis.konven.reinsurance') }}">Reinsurance</a></li>
                                </ul>
                            </li>
                            <li  class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Syariah</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('teknis.syariah.underwriting') }}">Underwriting</a></li>
                                    <li><a href="{{ route('teknis.syariah.reinsurance') }}">Reinsurance</a></li>
                                </ul>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 6) 
                            <!--Account Payable-->
                            <li><a href="{{route('bank-book.payable')}}" class="text-info px-1 icon-menu">Bank Book</a></li>
                            <li><a href="{{ route('expense.reinsurance-premium') }}" class="text-info px-1 icon-menu"> Reinsurance</a></li>
                            <li><a href="{{ route('expense.commision-payable') }}" class="text-info px-1 icon-menu"> Commision</a></li>
                            <li><a href="{{ route('expense-cancelation') }}" class="text-info px-1 icon-menu"> Cancelation</a></li>
                            <li><a href="{{ route('expense-refund') }}" class="text-info px-1 icon-menu"> Refund</a></li>
                            <li><a href="{{ route('expense.claim') }}" class="text-info px-1 icon-menu"> Claim Payable</a></li>
                            <li><a href="{{ route('expense.others') }}" class="text-info px-1 icon-menu"> Others</a></li>
                            <li><a href="{{ route('expense-handling-fee') }}" class="text-info px-1 icon-menu"> Handling Fee</a></li>
                        @endif
                        @if (\Auth::user()->user_access_id == 7)
                            <!--Account Receivable-->
                            <li><a href="{{ route('bank-book.teknik') }}" class="text-info px-1 icon-menu">Bank Book</a></li>
                            <li><a href="{{ route('income.premium-receivable') }}" class="text-info px-1 icon-menu"> Premium Receivable</a></li>
                            <li><a href="{{ route('income.reinsurance') }}" class="text-info px-1 icon-menu"> Reinsurance Commision</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1 icon-menu" data-toggle="dropdown">Recovery</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('income.recovery-claim') }}"> Recovery Claim</a></li>
                                    <li><a href="{{ route('income.recovery-refund') }}"> Recovery Refund</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('income.investment') }}" class="text-info px-1 icon-menu"> Invesment</a></li>
                            <li><a href="{{ route('income.titipan-premi') }}" class="text-info px-1 icon-menu"> Premium Deposit</a></li>
                            <li><a href="{{ route('others-income.index') }}" class="text-info px-1 icon-menu"> Others</a></li>
                        @endif
                    </ul>
                </div>
            </form>
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    {{-- <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        <a href="" class="icon-menu"><i class="fa fa-folder-open-o"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        <a href="" class="icon-menu"><i class="icon-calendar"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a href="" class="icon-menu"><i class="icon-bubbles"></i></a>
                    </li>
                    <li class="d-none d-sm-inline-block">
                        <a href="" class="icon-menu"><i class="icon-envelope"></i><span class="notification-dot"></span></a>
                    </li> --}}
                    {{-- <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                            <i class="icon-bell"></i>
                            <span class="notification-dot"></span>
                        </a>
                        <ul class="dropdown-menu notifications">
                            <li class="header"><strong>You have 0 new Notifications</strong></li>
                            <li class="footer"><a href="javascript:void(0);" class="more">See all notifications</a></li>
                        </ul>
                    </li> --}}
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i
                                class="icon-equalizer"></i></a>
                        <ul class="dropdown-menu user-menu menu-icon">
                            <li class="menu-heading">ACCOUNT SETTINGS</li>
                            <li><a href="{{ route('profile') }}"><i class="icon-note"></i> <span>My Profile</span></a>
                            </li>
                            <li><a href="{{ route('setting') }}"><i class="icon-equalizer"></i>
                                    <span>Setting</span></a>
                            </li>
                            <li><a href="{{ route('back-to-admin') }}" class="text-danger"><i
                                        class="fa fa-arrow-right"></i> <span>Back to Admin</span></a></li>
                        </ul>
                    </li>
                    <li><a href="" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="icon-menu"><i class="icon-login"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
