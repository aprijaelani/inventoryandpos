<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\WorkOrdersOut;
use App\Employee;

class WorkOrders extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['kode_wo','no_nota','employee_id', 'tanggal', 'tanggal_estimasi','tanggal_pengambilan','keterangan','pembayaran','status','dp','discount','pihak_ketiga'];

	public function work_order_out()
	{
		return $this->hasMany(WorkOrdersOut::class);
	}

	public function employee ()
	{
		return $this->belongsTo(Employee::class);
	}
}
