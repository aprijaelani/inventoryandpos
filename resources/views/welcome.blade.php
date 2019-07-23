<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>
                <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="">Optik Admin</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <!-- <li class="xn-profile">
                        <a href="#" class="profile-mini">
                            <img src="{{ asset('assets/images/users/avatar.jpg')}}" alt="John Doe"/>
                        </a>
                        <div class="profile">
                            <div class="profile-image">
                                <img src="{{ asset('assets/images/users/avatar.jpg')}}" alt="John Doe"/>
                            </div>
                            <div class="profile-data">
                                <div class="profile-data-name"></div>
                                <div class="profile-data-title">Indo Optik</div>
                            </div>
                        </div>                 
                    </li> --> 
                    {{$class_active}}
                    @if($class_active == 'home')
                        @php
                        $home = 'active';
                        $barang = '';
                        @endphp
                    @elseif($class_active == 'barang')
                        @php
                        $home = '';
                        $barang = 'active';
                        @endphp
                    @endif
                    <li><p></p></li>
                    <li class="{{$home}}">
                        <a href="/home"><span class="fa fa-dashboard"></span> <span class="xn-text">Beranda</span></a>            
                    </li>

                    <li class="xn-title">Transaksi</li>
                    <li class="xn-openable">
                      <a href="#">
                        <i class="fa fa-tasks"></i>
                        <span> Pembelian</span>
                      </a>
                      <ul class="treeview-menu">
                        <li class="xn-openable">
                            <a href=""><i class="fa fa-shopping-cart"></i> Pesan Barang</a>
                            <ul class="treeview-menu">
                                <li><a href="/list_po"><i class="fa fa-list"></i> Daftar Pesanan</a></li>
                                <li><a href="/po"><i class="fa fa-hospital-o"></i> Buat Pesanan</a></li>
                            </ul>
                        </li>
                        <li><a href="/penerimaan"><i class="fa fa-car"></i> Penerimaan Barang</a></li>
                        <li class="xn-openable">
                            <a href=""><i class="fa fa-file-o"></i> Faktur</a>
                            <ul class="treeview-menu">
                                <li><a href="/po_invoice"><i class="fa fa-files-o"></i> Faktur Pesanan Barang</a></li>
                                <li><a href="/real_invoice"><i class="glyphicon glyphicon-list-alt"></i> Faktur Langsung</a></li>
                            </ul>
                        </li>
                      </ul>
                    </li>
                    <li class="xn-openable">
                      <a href="#">
                        <i class="fa fa-tags"></i>
                        <span> Penjualan</span>
                      </a>
                      <ul class="treeview-menu">
                        <li class="xn-openable">
                            <a href=""><i class="fa fa-file-o"></i> Work Order</a>
                            <ul class="treeview-menu">
                                <li><a href="/wo"><i class="fa fa-files-o"></i> WO</a></li>
                                <li><a href="/daftar-wo"><i class="glyphicon glyphicon-list-alt"></i> Daftar WO</a></li>
                            </ul>
                        </li>
                        <li class="xn-openable">
                            <a href=""><i class="fa fa-tags"></i> Invoice</a>
                            <ul class="treeview-menu">
                                <li><a href="/wo-invoices"><i class="fa fa-files-o"></i> WO Invoice</a></li>
                                <li><a href="/sales-invoices"><i class="glyphicon glyphicon-list-alt"></i> Sales Invoice</a></li>
                            </ul>
                        </li>
                        <li>
                            <li><a href="/list-penjualan"><i class="glyphicon glyphicon-list-alt"></i> List Penjualan</a></li>
                        </li>
                      </ul>
                    </li>
                    <li class="xn-openable">
                      <a href="#">
                        <i class="fa fa-folder-open"></i>
                        <span> Inventory</span>
                      </a>
                      <ul class="treeview-menu">
                        <li>
                            <a href="/pindah-gudang"><i class="fa fa-share"></i> Pindah Gudang</a>
                        </li>
                                <li><a href="stok-opname"><i class="fa fa-exchange"></i> Stok Opname</a></li>
                                <li><a href="stok-masuk"><i class="fa fa-files-o"></i> Stok Masuk</a></li>
                                <li><a href="stok-keluar"><i class="glyphicon glyphicon-list-alt"></i> Stok Keluar</a></li>
                      </ul>
                    </li>
                    <li class="xn-openable">
                      <a href="#">
                        <i class="fa fa-money"></i>
                        <span> Finance</span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="/set_kas"><i class="fa fa-book"></i> Buka Kas</a></li>
                        <li><a href="/"><i class="fa fa-archive"></i> Tutup Kasir</a></li>
                        <li><a href="/kas-masuk"><i class="fa fa-inbox"></i> Kas Masuk</a></li>
                        <li><a href="/kas-keluar"><i class="fa fa-bar-chart-o"></i> Kas Keluar</a></li>
                      </ul>
                    </li>
                    <li class="xn-openable">
                      <a href="#">
                        <i class="fa fa-bar-chart-o"></i>
                        <span> Report</span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="/set_kas"><i class="fa fa-shopping-cart"></i> Laporan Penjualan</a></li>
                      </ul>
                    </li>

                    <li class="xn-title">Master</li>
                    <li class="{{$barang}}">
                        <a href="/barang"><i class="fa fa-navicon"></i> Daftar Barang</a>
                    </li>
                    <li>
                        <a href="/price_list"><span class="fa fa-usd"></span> <span class="xn-text">Daftar Harga</span></a>                        
                    </li>  
                    <li class="{{ active_menu(Route::currentRouteName(),'gudang', 0, 5) }}">
                        <a href="/gudang"><i class="fa fa-home"></i> Gudang</a>
                    </li>
                    <li class="{{ active_menu(Route::currentRouteName(),'grouping', 0, 8) }}">
                        <a href="/grouping"><i class="fa fa-sitemap"></i> Jenis Barang</a>
                    </li>
                    <li class="{{ active_menu(Route::currentRouteName(),'employee', 0, 8) }}">
                        <a href="/employee"><span class="fa fa-user"></span> <span class="xn-text">Sales</span></a>                        
                    </li>                   
                    <li class="{{ active_menu(Route::currentRouteName(),'supplier', 0, 8) }}">
                        <a href="/supplier"><span class="fa fa-suitcase"></span> <span class="xn-text">Supplier</span></a>                        
                    </li>               
                    <li class="{{ active_menu(Route::currentRouteName(),'user', 0, 4) }}">
                        <a href="/user"><span class="fa fa-users"></span> <span class="xn-text">User Akun</span></a>                        
                    </li>
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

                <div class="links">
                    <a href="https://laravel.com/docs">Documentation</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                </div>
            </div>
        </div>
    </body>
</html>
