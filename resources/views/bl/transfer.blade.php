@extends('layouts.app')

@section('title', 'Transfer Ownership')

@section('content')
<h1 class="text-2xl font-bold mb-2">Transfer Ownership</h1>
<p class="mb-6 text-gray-600">BL: <span class="font-mono">{{ $billOfLading->bl_number }}</span> | Current Owner: {{ $billOfLading->current_owner }}</p>

<form action="{{ route('bl.transfer', $billOfLading) }}" method="POST" class="bg-white shadow rounded-lg p-6 max-w-lg">
    @csrf
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Transfer To (New Owner)</label>
        <input type="text" name="to_owner" required class="w-full border rounded px-3 py-2" placeholder="Nama atau address baru">
    </div>
    <div class="mb-6">
        <label class="block text-sm font-medium mb-1">Reason / Notes</label>
        <input type="text" name="reason" class="w-full border rounded px-3 py-2" placeholder="e.g. Sold to buyer, Endorsed to bank">
    </div>
    <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded">
        Transfer & Catat di Blockchain
    </button>
</form>
@endsection
