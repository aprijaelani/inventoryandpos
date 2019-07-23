<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['barang_id','gudang_id', 'qty','harga_pokok', 'last_qty'];

	public function barang()
    {
    	return $this->belongsTo(Barang::class);
    }
}
