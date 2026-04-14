<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    @auth
    @php $role = strtolower(auth()->user()->role); @endphp

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="22">
            </span>
        </a>
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="22">
            </span>
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm-light.png') }}" alt="" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">

                @if($role == 'admin')
                <li class="menu-title">Dashboards</li>
                <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="monitor"></i>
                        <span class="menu-item">Dashboard</span>
                    </a>
                </li>
                <li class="menu-title">Menu</li>
                <li class="{{ request()->routeIs('user.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('user.tampil') }}" class="{{ request()->routeIs('user.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="users"></i>
                        <span class="menu-item">User</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('category.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('category.tampil') }}" class="{{ request()->routeIs('category.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item">Kategori</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('lokasi.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('lokasi.tampil') }}" class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="book"></i>
                        <span class="menu-item">Lokasi</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('alat.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('alat.tampil') }}" class="{{ request()->routeIs('alat.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="package"></i>
                        <span class="menu-item">Alat</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('peminjaman.*') ? 'mm-active' : '' }}">
                    <a href="#" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="folder"></i>
                        <span class="menu-item">Data Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon nav-icon" data-feather="folder"></i>
                        <span class="menu-item">Data Pengembalian</span>
                    </a>
                </li>
                @endif

                @if($role == 'employee')
                <li class="menu-title">Menu</li>
                <li class="{{ request()->routeIs('peminjaman.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('peminjaman.tampil') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="folder"></i>
                        <span class="menu-item">Pengajuan</span>
                    </a>
                </li>
                @endif

                @if($role == 'user')
                <li class="menu-title">Menu</li>
                <li class="{{ request()->routeIs('alat.*') ? 'mm-active' : '' }}">
                    <a href="{{ route('alat.tampil') }}" class="{{ request()->routeIs('alat.*') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="users"></i>
                        <span class="menu-item">Alat</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('peminjaman.tampil', 'peminjaman.tambah', 'peminjaman.store') ? 'mm-active' : '' }}">
                    <a href="{{ route('peminjaman.tampil') }}" class="{{ request()->routeIs('peminjaman.tampil', 'peminjaman.tambah', 'peminjaman.store') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="edit-3"></i>
                        <span class="menu-item">Pengajuan Peminjaman</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('peminjaman.riwayat') ? 'mm-active' : '' }}">
                    <a href="{{ route('peminjaman.riwayat') }}" class="{{ request()->routeIs('peminjaman.riwayat') ? 'active' : '' }}">
                        <i class="icon nav-icon" data-feather="file-text"></i>
                        <span class="menu-item">Riwayat</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </div>
    @endauth
</div>
<!-- Left Sidebar End -->