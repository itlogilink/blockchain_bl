@extends('layouts.app')

@section('title', 'Blockchain Explorer')

@section('content')
<h1 class="text-2xl font-bold mb-4">Blockchain Explorer</h1>

<div class="mb-4 p-3 rounded {{ $status['valid'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
    Chain Integrity: {{ $status['valid'] ? 'VALID' : 'INVALID' }} 
    | Total Blocks: {{ $status['total_blocks'] }}
</div>

<div class="space-y-3">
    @foreach($blocks as $block)
    <div class="bg-white shadow rounded-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <span class="font-mono font-bold text-lg">Block #{{ $block->index }}</span>
                <span class="ml-3 text-sm text-gray-500">{{ $block->timestamp->format('d M Y H:i:s') }}</span>
            </div>
            <span class="text-xs bg-gray-100 px-2 py-1 rounded">nonce: {{ $block->nonce }}</span>
        </div>
        <div class="mt-2 text-xs font-mono break-all text-gray-600">
            Hash: {{ $block->hash }}
        </div>
        <div class="mt-1 text-xs font-mono break-all text-gray-500">
            Prev: {{ $block->previous_hash }}
        </div>
        <div class="mt-2 text-sm">
            <strong>Data:</strong> 
            {{ $block->data['type'] ?? '' }} 
            @if(isset($block->data['bl_number']))
                | BL: {{ $block->data['bl_number'] }}
            @endif
            @if(isset($block->data['from']))
                | {{ $block->data['from'] }} → {{ $block->data['to'] }}
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $blocks->links() }}
</div>
@endsection
