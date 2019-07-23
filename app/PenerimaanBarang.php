<?php

namespace App;

use App\PreOrderOut;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PenerimaanBarang extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['kode_po','user_id','supplier_id','no_sj', 'tanggal', 'keterangan', 'status', 'tanggal_pembayaran', 'tanggal_estimasi', 'pembayaran','gudang_id'];

	public function pre_order_out()
    {
    	return $this->hasMany(PreOrderOut::class);
    }

    public function where_pre_order_out($supplier_id)
    {
        return $this->pre_order_out()->where('supplier_id','=', $supplier_id);
    }

    public function supplier()
    {
    	return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pre_order_out_trash()
    {
        return $this->hasMany(PreOrderOut::class)->withTrashed();
    }

    public function pre_order_in()
    {
        return $this->hasMany(PreOrderIn::class);   
    }
}
