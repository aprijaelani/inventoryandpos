<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeracaPengeluaran extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['neraca_id', 'date','total','status','keterangan', 'keterangan_id'];

    public function neraca_keuangan()
    {
    	return $this->belongsTo(NeracaKeuangan::class);
    }
}
