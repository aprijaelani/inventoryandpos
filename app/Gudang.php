<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Barang;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gudang extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['nama'];

    public function stock()
    {
        return $this->hasMany('App\Stock');   
    }
}
