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
                                    <li><a href="{{ route('produk.index') }}">Produk</a></li>
                                    <li><a href="{{ route('reasuradur.index') }}">Reasuradur</a></li>
                                    <li><a href="{{ route('users.index') }}">Users</a></li>
                                    <li><a href="{{ route('klaim-reason.index') }}">Klaim Reason</a></li>
                                    <li><a href="{{ route('rate-broker.index') }}">Rate Broker</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('polis.index') }}" class="text-info icon-menu px-1">Pemegang Polis</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Pengajuan</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('pengajuan.index') }}">Penambahan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Pembatalan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-refund.index') }}">Pengurangan / Refund</a></li>
                                    <li><a href="{{ route('reas.index') }}">Reas</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('peserta.index') }}" class="text-info icon-menu px-1">Database Peserta</a></li>
                            <li><a href="{{ route('klaim.index') }}" class="text-info icon-menu px-1">Klaim</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Reas</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('reas.index') }}">Pengajuan</a></li>
                                    <li><a href="{{ route('reas-cancel.index') }}">Cancel</a></li>
                                    <li><a href="{{ route('reas-refund.index') }}">Refund</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('recovery-claim.index') }}" class="text-info icon-menu px-1">Recovery Claim</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Memo</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('memo-ujroh.index') }}">Ujroh</a></li>
                                </ul>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 2)
                            <!-- Head Underwriting -->
                            <li><a href="{{ route('polis.index') }}" class="text-info">Pemegang Polis</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Pengajuan</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('pengajuan.index') }}">Penambahan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Pembatalan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-refund.index') }}">Pengurangan / Refund</a></li>
                                    <li><a href="{{ route('reas.index') }}">Reas</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('peserta.index') }}" class="text-info icon-menu px-1">Database Peserta</a></li>
                            <li><a href="{{ route('klaim.index') }}" class="text-info icon-menu px-1">Klaim</a></li>
                            <li><a href="{{ route('reas.index') }}" class="text-info icon-menu px-1">Reas</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Memo</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('memo-ujroh.index') }}">Ujroh</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Cancel</a></li>
                                </ul>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 3)
                            <!-- Head Teknik -->
                            <li><a href="{{ route('polis.index') }}" class="text-info px-1 icon-menu" class="text-info icon-menu px-1"> Pemegang Polis </a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Pengajuan</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('pengajuan.index') }}">Penambahan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Pembatalan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-refund.index') }}">Pengurangan / Refund</a></li>
                                    <li><a href="{{ route('reas.index') }}">Reas</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('peserta.index') }}" class="text-info icon-menu px-1">Database Peserta</a></li>
                            <li><a href="{{ route('klaim.index') }}" class="text-info icon-menu px-1">Klaim</a></li>
                            <li><a href="{{ route('reas.index') }}" class="text-info icon-menu px-1">Reas</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Memo</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('memo-ujroh.index') }}">Ujroh</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Cancel</a></li>
                                </ul>
                            </li>
                        @endif
                        @if (\Auth::user()->user_access_id == 4)
                            <!-- Head Syariah -->
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Data Master</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('extra-mortalita.index') }}">Extra Mortalita</a></li>
                                    <li><a href="{{ route('produk.index') }}">Produk</a></li>
                                    <li><a href="{{ route('reasuradur.index') }}">Reasuradur</a></li>
                                    <li><a href="{{ route('users.index') }}">Users</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('polis.index') }}" class="text-info icon-menu px-1">Pemegang Polis</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Pengajuan</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('pengajuan.index') }}">Penambahan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Pembatalan Kepesertaan</a></li>
                                    <li><a href="{{ route('memo-refund.index') }}">Pengurangan / Refund</a></li>
                                    <li><a href="{{ route('reas.index') }}">Reas</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('peserta.index') }}" class="text-info icon-menu px-1">Database Peserta</a></li>
                            <li><a href="{{ route('klaim.index') }}" class="text-info icon-menu px-1">Klaim</a></li>
                            <li><a href="{{ route('reas.index') }}" class="text-info icon-menu px-1">Reas</a></li>
                            <li class="dropdown">
                                <a href="#" class="text-info dropdown-toggle icon-menu px-1" data-toggle="dropdown">Memo</a>
                                <ul class="dropdown-menu user-menu menu-icon">
                                    <li><a href="{{ route('memo-ujroh.index') }}">Ujroh</a></li>
                                    <li><a href="{{ route('memo-cancel.index') }}">Cancel</a></li>
                                    <!-- <li><a href="{{ route('memo-refund.index') }}">Refund</a></li> -->
                                </ul>
                            </li>
                        @endif

                    </ul>
                </div>
            </form>
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        @if(\Auth::user()->name)
                            {{\Auth::user()->name}} {!!isset(\Auth::user()->access->name) ? '<br /><small>( '. \Auth::user()->access->name .' )</small>' : ''!!}
                        @endif
                    </li>
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
