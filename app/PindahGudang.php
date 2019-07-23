<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PindahGudang extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['user_id', 'gudang_asal', 'gudang_tujuan', 'tanggal', 'keterangan'];


    public function gudang()
    {
		return $this->hasOne(Gudang::class);
    }
}
