<header class="main-nav">
    <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i
                data-feather="settings"></i></a><img class="img-90 rounded-circle"
            src="{{ asset('assets') }}/images/dashboard/1.png" alt="">
        <div class="badge-bottom"><span class="badge badge-primary">{{ Auth::user()->relRole->nama }}</span></div><a
            href="javascript:void(0)">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->nama }}</h6>
        </a>
        <p class="mb-0 font-roboto">Smart Pos</p>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav"
                            href="{{ route('dashboard.' . Auth::user()->role_name) }}"><i
                                data-feather="home"></i><span>Dashboard</span></a></li>

                    @if (Auth::user()->role_name == 'superadmin')
                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                    data-feather="database"></i><span>Master Data</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="javascript:void(0)">Kategori</a></li>
                                <li><a href="javascript:void(0)">Satuan</a></li>
                                <li><a href="javascript:void(0)">Produk</a></li>
                                <li><a href="javascript:void(0)">Supplier</a></li>
                                <li><a href="javascript:void(0)">Pelanggan</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                    data-feather="file-text"></i><span>Laporan</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="javascript:void(0)">Laporan Pembelian</a></li>
                                <li><a href="javascript:void(0)">Laporan Penjualan</a></li>
                                <li><a href="javascript:void(0)">Laporan Stok</a></li>
                            </ul>
                        </li>

                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                    data-feather="users"></i><span>Manajemen</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="javascript:void(0)">Menu</a></li>
                                <li><a href="javascript:void(0)">Role</a></li>
                                <li><a href="javascript:void(0)">User</a></li>
                            </ul>
                        </li>

                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="javascript:void(0)"><i
                                    data-feather="activity"></i><span>Log Aktivitas</span></a></li>
                    @elseif(Auth::user()->role_name == 'inventori')
                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                    data-feather="folder"></i><span>Inventori</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="javascript:void(0)">Persediaan</a></li>
                                <li><a href="javascript:void(0)">Stok Opname</a></li>
                                <li><a href="javascript:void(0)">Mutasi</a></li>
                            </ul>
                        </li>

                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i
                                    data-feather="box"></i><span>Pembelian</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="javascript:void(0)">Pembelian Barang</a></li>
                                <li><a href="javascript:void(0)">Penerimaan Barang</a></li>
                            </ul>
                        </li>
                    @elseif(Auth::user()->role_name == 'kasir')
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="javascript:void(0)"><i
                                    data-feather="shopping-cart"></i><span>Transaksi Penjualan</span></a></li>
                        <li class="dropdown"><a class="nav-link menu-title link-nav" href="javascript:void(0)"><i
                                    data-feather="airplay"></i><span>Transaksi Penjualan</span></a></li>
                    @endif
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
