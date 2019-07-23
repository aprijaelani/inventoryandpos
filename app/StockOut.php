<?php

namespace App;

use App\WorkOrders;
use App\Stock;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOut extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $fillable = ['stock_id','invoice_id', 'qty'];

	public function work_order()
    {
    	return $this->belongsTo(WorkOrders::class);
    }

	public function stock()
    {
    	return $this->belongsTo(Stock::class);
    }
}
