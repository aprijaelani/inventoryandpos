<?php

namespace App;


use App\PreOrder;
use App\PreOrderIn;
use App\PreOrderOut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Barang;
use App\User;
use App\Supplier;

class PreOrderOut extends Model
{
    use SoftDeletes;

    protected $fillable = ['pre_order_id','barang_id','user_id','qty','harga_po'];

	protected $dates = ['deleted_at'];

	public function pre_order()
	{
		return $this->belongsTo('App\PreOrder')->withTrashed();
	}

	public function test()
	{
		return $this->belongsTo('App\PreOrder')->withTrashed();
	}

	public function user()
	{
		return $this->belongsTo(User::class);	
	}

	public function barang()
	{
		return $this->belongsTo(Barang::class);	
	}

	public function supplier()
	{
		return $this->belongsToThrough('App\Supplier','App\PreOrder');	
	}

	public function pre_order_out()
	{
		return $this->hasMany(PreOrderOut::class);	
	}

	public function pre_order_in()
	{
		return $this->hasOne(PreOrderIn::class);
	}
}
