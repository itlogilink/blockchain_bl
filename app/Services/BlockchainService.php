<?php

namespace App\Services;

use App\Models\Block;
use App\Models\BillOfLading;
use Illuminate\Support\Facades\Log;
use Web3\Web3;
use Web3\Contract;

class BlockchainService
{
    protected string $mode;
    protected ?Web3 $web3 = null;

    public function __construct()
    {
        $this->mode = config('blockchain.mode', 'local');

        if ($this->mode === 'ethereum') {
            $provider = config('blockchain.web3_provider');
            if ($provider) {
                $this->web3 = new Web3($provider);
            }
        }
    }

    public function issueBill(BillOfLading $bl): array
    {
        $data = [
            'type' => 'ISSUE',
            'bl_number' => $bl->bl_number,
            'owner' => $bl->current_owner,
            'data_hash' => $bl->data_hash,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($this->mode === 'local') {
            return $this->addLocalBlock($data);
        }

        return $this->addEthereumTransaction($data);
    }

    public function transferOwnership(BillOfLading $bl, string $from, string $to, string $reason = 'transfer'): array
    {
        $data = [
            'type' => 'TRANSFER',
            'bl_number' => $bl->bl_number,
            'from' => $from,
            'to' => $to,
            'reason' => $reason,
            'data_hash' => $bl->data_hash,
            'timestamp' => now()->toIso8601String(),
        ];

        if ($this->mode === 'local') {
            return $this->addLocalBlock($data);
        }

        return $this->addEthereumTransaction($data);
    }

    protected function addLocalBlock(array $data): array
    {
        $lastBlock = Block::orderBy('index', 'desc')->first();

        $index = $lastBlock ? $lastBlock->index + 1 : 0;
        $previousHash = $lastBlock ? $lastBlock->hash : '0';

        $block = new Block([
            'index' => $index,
            'timestamp' => now(),
            'data' => $data,
            'previous_hash' => $previousHash,
            'nonce' => 0,
        ]);

        while (!str_starts_with($block->calculateHash(), '00')) {
            $block->nonce++;
        }

        $block->hash = $block->calculateHash();
        $block->save();

        return [
            'success' => true,
            'block_id' => $block->id,
            'hash' => $block->hash,
            'tx_hash' => $block->hash,
            'mode' => 'local',
        ];
    }

    protected function addEthereumTransaction(array $data): array
    {
        if (!$this->web3) {
            Log::warning('Web3 provider not configured, falling back to local');
            return $this->addLocalBlock($data);
        }

        Log::info('Ethereum mode selected but smart contract integration pending', $data);

        return $this->addLocalBlock($data + ['_ethereum_pending' => true]);
    }

    public function verifyChain(): array
    {
        $blocks = Block::orderBy('index')->get();
        $errors = [];

        foreach ($blocks as $i => $block) {
            if (!$block->isValid()) {
                $errors[] = "Block #{$block->index} hash invalid";
            }
            if ($i > 0 && $block->previous_hash !== $blocks[$i - 1]->hash) {
                $errors[] = "Block #{$block->index} previous_hash mismatch";
            }
        }

        return [
            'valid' => empty($errors),
            'total_blocks' => $blocks->count(),
            'errors' => $errors,
        ];
    }

    public function getHistory(string $blNumber): array
    {
        return Block::whereJsonContains('data->bl_number', $blNumber)
            ->orderBy('index')
            ->get()
            ->toArray();
    }
}
