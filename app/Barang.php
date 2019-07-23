<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplier;
use App\Grouping;
use App\PriceList;
use App\Stock;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['kode_supplier','supplier_id', 'kode_barcode', 'nama_barang','grouping_id','status'];

	public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function grouping()
    {
    	return $this->belongsTo(Grouping::class);
    }

    public function price_list ()
    {
        return $this->hasOne(PriceList::class);
    }

    public function stock ()
    {
        return $this->hasMany(Stock::class);
    }
}