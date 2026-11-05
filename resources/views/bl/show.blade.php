@extends('layouts.app')

@section('title', $billOfLading->bl_number)

@section('content')
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $billOfLading->bl_number }}</h1>
        <p class="text-gray-600">Issued: {{ $billOfLading->issued_at?->format('d M Y H:i') }}</p>
    </div>
    <a href="{{ route('bl.transfer.form', $billOfLading) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">
        Transfer Ownership
    </a>
</div>

<div class="grid grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="font-bold mb-4">Document Details</h2>
        <dl class="space-y-2 text-sm">
            <div class="flex"><dt class="w-40 text-gray-500">Shipper</dt><dd>{{ $billOfLading->shipper }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Consignee</dt><dd>{{ $billOfLading->consignee }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Carrier</dt><dd>{{ $billOfLading->carrier }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Vessel / Voyage</dt><dd>{{ $billOfLading->vessel_name }} / {{ $billOfLading->voyage_no }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">POL → POD</dt><dd>{{ $billOfLading->port_of_loading }} → {{ $billOfLading->port_of_discharge }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Cargo</dt><dd>{{ $billOfLading->cargo_description }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Qty / Weight</dt><dd>{{ $billOfLading->quantity }} / {{ $billOfLading->weight }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Current Owner</dt><dd class="font-medium">{{ $billOfLading->current_owner }}</dd></div>
            <div class="flex"><dt class="w-40 text-gray-500">Status</dt><dd>{{ $billOfLading->status }}</dd></div>
        </dl>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="font-bold mb-4">Blockchain Integrity</h2>
        <div class="space-y-3 text-sm">
            <div>
                <span class="text-gray-500">Data Hash:</span>
                <code class="block text-xs break-all bg-gray-100 p-2 rounded mt-1">{{ $billOfLading->data_hash }}</code>
            </div>
            <div>
                Integrity Check: 
                <span class="{{ $integrity ? 'text-green-600' : 'text-red-600' }} font-medium">
                    {{ $integrity ? 'VALID ✓' : 'INVALID ✗' }}
                </span>
            </div>
            <div>
                <span class="text-gray-500">Tx / Block Hash:</span>
                <code class="block text-xs break-all bg-gray-100 p-2 rounded mt-1">{{ $billOfLading->blockchain_tx_hash ?? '-' }}</code>
            </div>
        </div>
    </div>
</div>

@if($billOfLading->transfers->count())
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <h2 class="font-bold mb-4">Transfer History</h2>
    <table class="min-w-full text-sm">
        <thead><tr class="text-left text-gray-500"><th>From</th><th>To</th><th>Reason</th><th>Time</th></tr></thead>
        <tbody>
            @foreach($billOfLading->transfers as $t)
            <tr class="border-t">
                <td class="py-2">{{ $t->from_owner }}</td>
                <td>{{ $t->to_owner }}</td>
                <td>{{ $t->reason }}</td>
                <td>{{ $t->transferred_at->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if(count($history))
<div class="mt-6 bg-white shadow rounded-lg p-6">
    <h2 class="font-bold mb-4">On-Chain History</h2>
    <div class="space-y-2 text-sm">
        @foreach($history as $block)
        <div class="border-l-4 border-blue-400 pl-3 py-1">
            <span class="font-mono text-xs">#{{ $block['index'] }}</span>
            <span class="ml-2">{{ $block['data']['type'] ?? '' }}</span>
            <span class="text-gray-500 ml-2">{{ $block['timestamp'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
