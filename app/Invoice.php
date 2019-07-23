<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['penerimaan_barang_id','pre_order_in_id','total_harga','keterangan'];

	public function pre_order()
	{
        return $this->belongsTo('App\PreOrder');
	}
}
