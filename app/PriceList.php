<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Barang;

class PriceList extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['barang_id','min_harga', 'max_harga'];

	public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }
}
