<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Barang;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grouping extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	
    protected $fillable = ['nama'];

    public function barang()
    {
    	return $this->hasMany(Barang::class);
    }
}
