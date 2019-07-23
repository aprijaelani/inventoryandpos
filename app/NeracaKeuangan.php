<?php

namespace App;

use App\NeracaPemasukan;
use App\NeracaPengeluaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeracaKeuangan extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['tanggal','total','last_total','status','status_pembayaran','user_id'];

    public function neraca_pemasukan()
    {
    	return $this->hasMany(NeracaPemasukan::class);
    }

    public function neraca_pengeluaran()
    {
    	return $this->hasMany(NeracaPengeluaran::class);	
    }
}
