<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::post('/group/store', 'GroupController@store');
//delete controller
Route::post('/employee/delete', 'EmployeeController@destroy');
Route::post('/supplier/delete', 'SupplierController@destroy');
Route::post('/grouping/delete', 'GroupingController@destroy');
Route::post('/gudang/delete', 'GudangController@destroy');
Route::post('/barang/delete', 'BarangController@destroy');
Route::post('/biaya-pendapatan/delete', 'BiayaPendapatanController@destroy');
Route::post('/user/delete', 'UserController@destroy');
Route::post('/po/delete', 'PreOrderController@destroy');
Route::post('/real_invoice/delete', 'RealInvoiceController@destroy');

Route::group(['prefix'=>'home'], function(){
	Route::get('','HomeController@index');
});

Route::group(['prefix'=>'user'], function(){
	Route::get('','UserController@index');
	Route::get('create', 'UserController@create');
	Route::post('create', 'UserController@store');
	Route::get('{user}/edit', 'UserController@edit');
	Route::post('{user}/update', 'UserController@update');
	Route::get('check', 'UserController@checkUsername');
	Route::post('check_username', 'UserController@check_username');
});

Route::group(['prefix'=>'employee'], function(){
	Route::get('','EmployeeController@index');
	Route::get('create', 'EmployeeController@create');
	Route::post('create', 'EmployeeController@store');
	Route::post('check_sales', 'EmployeeController@checkSales');
	Route::get('{employee}/edit', 'EmployeeController@edit');
	Route::post('{employee}/update', 'EmployeeController@update');
});

Route::group(['prefix'=>'supplier'], function(){
	Route::get('','SupplierController@index');
	Route::get('create', 'SupplierController@create');
	Route::post('create', 'SupplierController@store');
	Route::post('check_supplier', 'SupplierController@checkSupplier');
	Route::get('{supplier}/edit', 'SupplierController@edit');
	Route::post('{supplier}/update', 'SupplierController@update');
	Route::post('search', 'SupplierController@search');
});

Route::group(['prefix'=>'grouping'], function(){
	Route::get('','GroupingController@index');
	Route::get('create', 'GroupingController@create');
	Route::post('create', 'GroupingController@store');
	Route::post('check_group', 'GroupingController@checkGroup');
	Route::get('{grouping}/edit', 'GroupingController@edit');
	Route::post('{grouping}/update', 'GroupingController@update');
});

Route::group(['prefix'=>'gudang'], function(){
	Route::get('','GudangController@index');
	Route::get('create', 'GudangController@create');
	Route::post('create', 'GudangController@store');
	Route::post('check_gudang', 'GudangController@checkGudang');
	Route::get('{gudang}/edit', 'GudangController@edit');
	Route::post('{gudang}/update', 'GudangController@update');
});

Route::group(['prefix'=>'barang'], function(){
	Route::get('','BarangController@index');
	Route::get('create', 'BarangController@create');
	Route::post('create', 'BarangController@store');
	Route::get('{barang}/edit', 'BarangController@edit');
	Route::post('{barang}/update', 'BarangController@update');
	Route::post('search', 'BarangController@search');
	Route::get('search', 'BarangController@search');
	Route::get('{barang}/barcode', 'PDFController@barcode');
	Route::get('{barang}/pdf', 'PDFController@pdf');
	Route::post('check_kode', 'BarangController@checkKode');
	Route::post('check_barang', 'BarangController@checkBarang');
});

Route::group(['prefix'=>'price_list'], function(){
	Route::get('','PriceListController@index')->name('index_barang');
	Route::get('create', 'PriceListController@create');
	Route::post('create', 'PriceListController@store');
	Route::get('{price_list}/edit', 'PriceListController@edit');
	Route::post('{price_list}/update', 'PriceListController@update');
	Route::post('search', 'PriceListController@search');
	Route::get('search', 'PriceListController@search');
});

Route::group(['prefix'=>'biaya-pendapatan'], function(){
	Route::get('','BiayaPendapatanController@index')->name('index_barang');
	Route::get('create', 'BiayaPendapatanController@create');
	Route::post('create', 'BiayaPendapatanController@store');
	Route::get('{biayaPendapatan}/edit', 'BiayaPendapatanController@edit');
	Route::post('{biayaPendapatan}/update', 'BiayaPendapatanController@update');
	Route::post('search', 'BiayaPendapatanController@search');
	Route::get('search', 'BiayaPendapatanController@search');
});


//Transasi Modul
Route::group(['prefix'=>'po'], function(){
	Route::get('','PreOrderController@index');
	Route::get('create', 'PreOrderController@create');
	Route::post('store', 'PreOrderController@store');
	Route::get('{pre_order}/edit', 'PreOrderController@edit');
	Route::post('{pre_order}/update', 'PreOrderController@update');
	Route::post('{pre_order}/updatepreorder', 'PreOrderController@updatePreOrder');
	Route::get('barang', 'PreOrderController@barang');
	Route::get('/kode_barcode/{id}', 'PreOrderController@kodebarang');
	Route::get('/nama_barang/{id}', 'PreOrderController@nama_barang');
	Route::get('/harga_pokok/{id}', 'PreOrderController@harga_pokok');

});

Route::group(['prefix'=>'list_po'], function(){
	Route::get('','PreOrderListController@index');
	Route::get('detail', 'PreOrderListController@edit');
	Route::post('detail', 'PreOrderListController@store');
	Route::get('{barang}/detail', 'PreOrderListController@edit');
	Route::post('search', 'PreOrderListController@search');
	Route::get('search', 'PreOrderListController@search');
});

Route::group(['prefix'=>'real_invoice'], function(){
	Route::get('','RealInvoiceController@index');
	Route::get('create', 'RealInvoiceController@create');
	Route::post('store', 'RealInvoiceController@store');
	Route::get('{pre_order}/edit', 'RealInvoiceController@edit');
	Route::post('{pre_order}/update', 'RealInvoiceController@update');
	Route::post('{pre_order}/selesaiinvoice', 'RealInvoiceController@updateInvoice');
	Route::get('barang', 'RealInvoiceController@barang');
	Route::post('/kode_barcode', 'RealInvoiceController@kodebarang');
	Route::post('/supplier', 'RealInvoiceController@supplier');
	Route::get('/nama_barang/{id}', 'RealInvoiceController@nama_barang');
	Route::get('/harga_pokok/{id}', 'RealInvoiceController@harga_pokok');

});

Route::group(['prefix'=>'penerimaan'], function(){
	Route::get('','PreOrderInController@index');
	Route::get('create', 'PreOrderInController@create');
	Route::post('show-po', 'PreOrderInController@showPo');
	Route::post('show-supplier', 'PreOrderInController@showSupplier');
	Route::post('update', 'PreOrderInController@update');
	Route::post('updatesupplier', 'PreOrderInController@updatesupplier');
	Route::post('check_no_sj', 'PreOrderInController@checkNoSJ');
});

Route::group(['prefix'=>'po_invoice'], function(){
	Route::get('','InvoiceController@index');
	Route::get('{penerimaan_barang}','InvoiceController@create');
	Route::post('create-invoice','InvoiceController@createInvoice');
	Route::post('store', 'InvoiceController@store');
});

Route::group(['prefix'=>'set_kas'], function(){
	Route::get('','NeracaPemasukanController@index');
	Route::post('create', 'NeracaPemasukanController@store');
});

Route::group(['prefix'=>'wo'], function(){
	Route::get('','WorkOrdersController@index');
	Route::get('/kode_barcode/{id}', 'WorkOrdersController@kodebarang');
	Route::post('/store', 'WorkOrdersController@store');
	Route::post('/check_harga', 'WorkOrdersController@checkHarga');
	Route::post('/check_nota', 'WorkOrdersController@checkNota');
});

Route::group(['prefix'=>'sales-invoices'], function(){
	Route::get('','WorkOrdersInController@index');
	Route::get('/kode_barcode/{id}', 'WorkOrdersController@kodebarang');
	Route::post('/store', 'WorkOrdersInController@store');
});

Route::group(['prefix'=>'wo-invoices'], function(){
	Route::get('','WorkOrdersInController@wo_invoices');
	Route::post('/work-order', 'WorkOrdersInController@searchWorkOrders');
	Route::post('/show-work-order', 'WorkOrdersInController@showWorkOrders');
	Route::post('/invoice', 'WorkOrdersInController@invoice');
});

Route::group(['prefix'=>'daftar-wo'], function(){
	Route::get('','WorkOrdersController@daftar_wo');
	Route::get('/work-order/{id}', 'WorkOrdersInController@searchWorkOrders');
	Route::post('/store', 'WorkOrdersInController@store');
});

Route::group(['prefix'=>'list-penjualan'], function(){
	Route::get('','WorkOrdersController@list_penjualan');
	Route::post('/filter_list','WorkOrdersController@filter_list');
	Route::post('search', 'WorkOrdersController@search');
	Route::get('search', 'WorkOrdersController@search');
});

Route::group(['prefix'=>'pindah-gudang'], function(){
	Route::get('','PindahGudangController@index');
	Route::post('/proses','PindahGudangController@store');
	Route::post('/barang','PindahGudangController@barang');
});

Route::group(['prefix'=>'stok-opname'], function(){
	Route::get('','StockOpnameController@index');
	Route::post('/proses','StockOpnameController@store');
	Route::post('/barang','StockOpnameController@barang');
});

Route::group(['prefix'=>'stok-masuk'], function(){
	Route::get('','StockMasukController@index');
	Route::post('/proses','StockMasukController@store');
	Route::post('/check','StockMasukController@barang');
});

Route::group(['prefix'=>'stok-keluar'], function(){
	Route::get('','StockKeluarController@index');
	Route::post('/proses','StockKeluarController@store');
	Route::post('/barang','StockKeluarController@barang');
});

Route::group(['prefix'=>'kas-masuk'], function(){
	Route::get('','KasMasukController@index');
	Route::post('/proses','KasMasukController@store');
	Route::post('/barang','KasMasukController@barang');
	Route::post('/biaya_pendapatan','KasMasukController@biaya_pendapatan');
});

Route::group(['prefix'=>'kas-keluar'], function(){
	Route::get('','KasKeluarController@index');
	Route::post('/proses','KasKeluarController@store');
	Route::post('/barang','KasKeluarController@barang');
	Route::post('/biaya_pendapatan','KasKeluarController@biaya_pendapatan');
});

Route::group(['prefix'=>'laporan-harian'], function(){
	Route::get('','LaporanHarianController@index');
	Route::post('/proses','LaporanHarianController@store');
	Route::post('/cari','LaporanHarianController@search');
	Route::get('/cari','LaporanHarianController@search');
});

Route::group(['prefix'=>'import'], function(){
	Route::get('/barang','ImportController@index');
	Route::get('/price_list','ImportController@price_list');
	Route::post('/import','ImportController@store');
	Route::post('/import-price-list','ImportController@import_price_list');
});

