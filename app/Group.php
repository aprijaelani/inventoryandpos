<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	protected $dates = ['deleted_at'];
	
    protected $fillable = ['nama'];
}
