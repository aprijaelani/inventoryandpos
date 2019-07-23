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
                    <li><p></p></li>
                    <li class="{{ setActived('home') }}">
                        <a href="/home"><span class="fa fa-dashboard"></span> <span class="xn-text">Beranda</span></a>            
                    </li>

                    <li class="xn-title">Transaksi</li>
                    <li class="xn-openable {{ setActived('list_po') }} {{ setActived('penerimaan') }} {{ setActive('po') }}{{ setActived('po_invoice') }} {{ setActived('real_invoice') }} ">
                      <a href="#">
                        <i class="fa fa-tasks"></i>
                        <span> Pembelian</span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="xn-openable {{ setActived('list_po') }} {{ setActive('po') }}">
                            <a href=""><i class="fa fa-shopping-cart"></i> Pesan Barang</a>
                            <ul class="treeview-menu">
                                <li class="{{ setActived('list_po') }}"><a href="/list_po"><i class="fa fa-list"></i> Daftar Pesanan</a></li>
                                <li class="{{ setActive('po') }}"><a href="/po"><i class="fa fa-hospital-o"></i> Buat Pesanan</a></li>
                            </ul>
                        </li>
                        <li class="{{ setActive('penerimaan') }}{{ setActive('penerimaan/show-po') }}{{ setActive('penerimaan/show-supplier') }}"><a href="/penerimaan"><i class="fa fa-car"></i> Penerimaan Barang</a></li>
                        <li class="xn-openable {{ setActived('po_invoice') }}{{ setActive('real_invoice') }}">
                            <a href=""><i class="fa fa-file-o"></i> Faktur</a>
                            <ul class="treeview-menu">
                                <li class="{{ setActived('po_invoice') }}"><a href="/po_invoice"><i class="fa fa-files-o"></i> Faktur Pesanan Barang</a></li>
                                <li class="{{ setActive('real_invoice') }}"><a href="/real_invoice"><i class="glyphicon glyphicon-list-alt"></i> Faktur Langsung</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="xn-openable {{ setActive('daftar-wo') }} {{ setActive('wo-invoices') }} {{ setActive('sales-invoices') }} {{ setActive('wo') }}{{ setActived('list-penjualan') }}">
                  <a href="#">
                    <i class="fa fa-tags"></i>
                    <span> Penjualan</span>
                </a>
                <ul class="treeview-menu">
                    <li class="xn-openable {{ setActive('daftar-wo') }} {{ setActive('wo') }}">
                        <a href=""><i class="fa fa-file-o"></i> Work Order</a>
                        <ul class="treeview-menu">
                            <li class="{{ setActive('wo') }}"><a href="/wo"><i class="fa fa-files-o"></i> WO</a></li>
                            <li class="{{ setActive('daftar-wo') }}"><a href="/daftar-wo"><i class="glyphicon glyphicon-list-alt"></i> Daftar WO</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable  {{ setActive('wo-invoices') }}{{ setActive('sales-invoices') }}">
                        <a href=""><i class="fa fa-tags"></i> Invoice</a>
                        <ul class="treeview-menu">
                            <li class="{{ setActive('wo-invoices') }}"><a href="/wo-invoices"><i class="fa fa-files-o"></i> WO Invoice</a></li>
                            <li class="{{ setActive('sales-invoices') }}"><a href="/sales-invoices"><i class="glyphicon glyphicon-list-alt"></i> Sales Invoice</a></li>
                        </ul>
                    </li>
                    <li class="{{ setActived('list-penjualan') }} ">
                        <li><a href="/list-penjualan"><i class="glyphicon glyphicon-list-alt"></i> List Penjualan</a></li>
                    </li>
                </ul>
            </li>
            <li class="xn-openable {{ setActive('pindah-gudang') }}{{ setActive('stok-opname') }}{{ setActive('stok-masuk') }}{{ setActive('stok-keluar') }}">
              <a href="#">
                <i class="fa fa-folder-open"></i>
                <span> Inventory</span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ setActive('pindah-gudang') }}"><a href="/pindah-gudang"><i class="fa fa-share"></i> Pindah Gudang</a></li>
                <li class="{{ setActive('stok-opname') }}"><a href="/stok-opname"><i class="fa fa-exchange"></i> Stok Opname</a></li>
                <li class="{{ setActive('stok-masuk') }}"><a href="/stok-masuk"><i class="fa fa-files-o"></i> Stok Masuk</a></li>
                <li class="{{ setActive('stok-keluar') }}"><a href="/stok-keluar"><i class="glyphicon glyphicon-list-alt"></i> Stok Keluar</a></li>
            </ul>
        </li>
        <li class="xn-openable {{ setActive('set_kas') }} {{ setActive('tutup-kasir') }} {{ setActive('kas-masuk') }}{{ setActive('kas-keluar') }}{{ setActive('laporan-harian') }}">
          <a href="#">
            <i class="fa fa-money"></i>
            <span> Finance</span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ setActive('set_kas') }}"><a href="/set_kas"><i class="fa fa-book"></i> Buka Kas</a></li>
            <li class="{{ setActive('kas-masuk') }}"><a href="/kas-masuk"><i class="fa fa-inbox"></i> Kas Masuk</a></li>
            <li class="{{ setActive('kas-keluar') }}"><a href="/kas-keluar"><i class="fa fa-bar-chart-o"></i> Kas Keluar</a></li>
            <li class="{{ setActive('laporan-harian') }}"><a href="/laporan-harian"><i class="fa fa-archive"></i> Laporan Harian</a></li>
        </ul>
    </li>

    <li class="xn-title">Master</li>
    <li class="{{ setActived('barang') }}">
        <a href="/barang"><i class="fa fa-navicon"></i> Barang</a>
    </li>
    <li class="{{ setActived('price_list') }}">
        <a href="/price_list"><span class="fa fa-usd"></span> <span class="xn-text">Daftar Harga</span></a>                        
    </li>  
    <li class="{{ setActived('gudang') }}">
        <a href="/gudang"><i class="fa fa-home"></i> Gudang</a>
    </li>
    <li class="{{ setActived('grouping') }}">
        <a href="/grouping"><i class="fa fa-sitemap"></i> Jenis Barang</a>
    </li>
    <li class="{{ setActived('employee') }}">
        <a href="/employee"><span class="fa fa-user"></span> <span class="xn-text">Sales</span></a>                        
    </li>                   
    <li class="{{ setActived('supplier') }}">
        <a href="/supplier"><span class="fa fa-suitcase"></span> <span class="xn-text">Supplier</span></a>                        
    </li>                   
    <li class="{{ setActived('biaya-pendapatan') }}">
        <a href="/biaya-pendapatan"><span class="fa fa-exchange"></span> <span class="xn-text">Biaya Pendapatan</span></a>                        
    </li>               
    <li class="{{ setActived('user') }}">
        <a href="/user"><span class="fa fa-users"></span> <span class="xn-text">User Akun</span></a>                        
    </li>
</ul>
<!-- END X-NAVIGATION -->
</div>
<!-- END PAGE SIDEBAR -->
