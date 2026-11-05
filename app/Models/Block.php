<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'index',
        'timestamp',
        'data',
        'previous_hash',
        'hash',
        'nonce',
    ];

    protected $casts = [
        'data' => 'array',
        'timestamp' => 'datetime',
    ];

    public function calculateHash(): string
    {
        $payload = $this->index . $this->timestamp . json_encode($this->data) . $this->previous_hash . $this->nonce;
        return hash('sha256', $payload);
    }

    public function isValid(): bool
    {
        return $this->hash === $this->calculateHash();
    }
}
