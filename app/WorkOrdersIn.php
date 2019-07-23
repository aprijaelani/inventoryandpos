<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\WorkOrders;

class WorkOrdersIn extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['work_order_id','sales_id', 'barang_id', 'qty','harga_wo'];

	public function work_orders()
	{
		return $this->belongsTo(WorkOrders::class);
	}
}
