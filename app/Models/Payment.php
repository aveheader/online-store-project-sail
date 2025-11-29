<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $order
 */
class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'gateway_response',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
