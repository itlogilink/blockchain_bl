<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BillOfLading extends Model
{
    protected $fillable = [
        'bl_number',
        'shipper',
        'consignee',
        'notify_party',
        'carrier',
        'vessel_name',
        'voyage_no',
        'port_of_loading',
        'port_of_discharge',
        'cargo_description',
        'quantity',
        'weight',
        'container_numbers',
        'current_owner',
        'status',
        'data_hash',
        'blockchain_tx_hash',
        'block_id',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'container_numbers' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($bl) {
            if (empty($bl->bl_number)) {
                $bl->bl_number = 'eBL-' . strtoupper(Str::random(8));
            }
            $bl->issued_at = now();
            $bl->status = 'issued';
        });
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function generateDataHash(): string
    {
        $data = [
            $this->bl_number,
            $this->shipper,
            $this->consignee,
            $this->carrier,
            $this->vessel_name,
            $this->voyage_no,
            $this->port_of_loading,
            $this->port_of_discharge,
            $this->cargo_description,
            $this->quantity,
            $this->weight,
            json_encode($this->container_numbers),
        ];

        return hash('sha256', implode('|', $data));
    }

    public function verifyIntegrity(): bool
    {
        return $this->data_hash === $this->generateDataHash();
    }
}
