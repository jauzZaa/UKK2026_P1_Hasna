<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

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
                <li class="menu-title">Dashboards</li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="icon nav-icon" data-feather="monitor"></i>
                        <span class="menu-item">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Menu</li>
                <li>
                    <a href="{{ route('user.tampil') }}">
                        <i class="icon nav-icon" data-feather="users"></i>
                        <span class="menu-item">User</span>
                    </a>

                </li>

                <li>
                    <a href="{{ route('category.tampil') }}">
                        <i class="icon nav-icon" data-feather="trello"></i>
                        <span class="menu-item" data-key="t-kanban">Kategori</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('lokasi.tampil') }}">
                        <i class="icon nav-icon" data-feather="book"></i>
                        <span class="menu-item" data-key="t-contacts">Lokasi</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('alat.tampil') }}">
                        <i class="icon nav-icon" data-feather="package"></i>
                        <span class="menu-item" data-key="t-ui-elements">Alat</span>
                    </a>
                </li>





                <li>
                    <a href="{{ route('peminjaman.tampil') }}">
                        <i class="icon nav-icon" data-feather="folder"></i>
                        <span class="menu-item" data-key="t-filemanager">Data Peminjaman</span>
                    </a>
                </li>

                <li>
                    <a href="apps-file-manager.html">
                        <i class="icon nav-icon" data-feather="folder"></i>
                        <span class="menu-item" data-key="t-filemanager">Data Pengembalian</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->