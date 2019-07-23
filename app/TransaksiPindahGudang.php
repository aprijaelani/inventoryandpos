<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPindahGudang extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['pindah_gudang_id', 'barang_id', 'qty'];
}
