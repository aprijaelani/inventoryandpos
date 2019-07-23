<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['nama'];

    public function preorders()
    {
        return $this->hasMany('App\PreOrder');
    }

    public function pre_order_out()
    {
    	return $this->hasManyThrough('App\PreOrderOut','App\PreOrder');	
    }

    public function total_qty() 
    {
        return $this->hasOne(PreOrderOut::class, 'pre_order_id')->selectRaw('pre_order_id, sum(qty) as total_qty')->groupBy('pre_order_id');
    }

    public function barang()
    {
        return $this->hasMany('App\Barang');   
    }
}
