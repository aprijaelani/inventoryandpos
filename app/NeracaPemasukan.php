<?php

namespace App;

use App\NeracaKeuangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NeracaPemasukan extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['neraca_id','total','keterangan', 'keterangan_id'];

    public function neraca_keuangan()
    {
    	return $this->belongsTo(NeracaKeuangan::class);
    }
}
