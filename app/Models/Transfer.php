<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'bill_of_lading_id',
        'from_owner',
        'to_owner',
        'reason',
        'blockchain_tx_hash',
        'transferred_at',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function billOfLading()
    {
        return $this->belongsTo(BillOfLading::class);
    }
}
