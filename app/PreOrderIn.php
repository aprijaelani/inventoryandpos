<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Barang;
use App\PreOrderOut;
use App\PenerimaanBarang;
use Illuminate\Support\Facades\Auth;

class PreOrderIn extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['penerimaan_barang_id','pre_order_out_id','user_id','barang_id','qty','no_sj','harga_po'];

	public function pre_order()
	{
		return $this->belongsTo(PreOrder::class)->withTrashed();
	}

	public function user()
	{
		return $this->belongsTo(User::class);	
	}

	public function barang()
	{
		return $this->belongsTo(Barang::class);	
	}

	public function pre_order_out()
	{
		return $this->belongsTo(PreOrderOut::class);
	}

	public function penerimaan_barang()
	{
		return $this->belongsTo(PenerimaanBarang::class);
	}
}
