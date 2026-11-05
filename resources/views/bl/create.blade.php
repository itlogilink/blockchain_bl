@extends('layouts.app')

@section('title', 'Issue Electronic Bill of Lading')

@section('content')
<h1 class="text-2xl font-bold mb-6">Issue Electronic Bill of Lading Baru</h1>

<form action="{{ route('bl.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Shipper</label>
            <input type="text" name="shipper" required class="w-full border rounded px-3 py-2" value="{{ old('shipper') }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Consignee</label>
            <input type="text" name="consignee" required class="w-full border rounded px-3 py-2" value="{{ old('consignee') }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Carrier</label>
            <input type="text" name="carrier" required class="w-full border rounded px-3 py-2" value="{{ old('carrier') }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Current Owner (awal)</label>
            <input type="text" name="current_owner" required class="w-full border rounded px-3 py-2" value="{{ old('current_owner', old('shipper')) }}">
        </div>
        <div>
            <label class="block text-sm font-medium">Vessel Name</label>
            <input type="text" name="vessel_name" required class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Voyage No</label>
            <input type="text" name="voyage_no" required class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Port of Loading</label>
            <input type="text" name="port_of_loading" required class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium">Port of Discharge</label>
            <input type="text" name="port_of_discharge" required class="w-full border rounded px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium">Cargo Description</label>
        <textarea name="cargo_description" required rows="3" class="w-full border rounded px-3 py-2"></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Quantity</label>
            <input type="text" name="quantity" required class="w-full border rounded px-3 py-2" placeholder="e.g. 100 CTNS">
        </div>
        <div>
            <label class="block text-sm font-medium">Weight</label>
            <input type="text" name="weight" required class="w-full border rounded px-3 py-2" placeholder="e.g. 2500 KGS">
        </div>
    </div>

    <div class="pt-4">
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-medium">
            Issue eBL & Catat di Blockchain
        </button>
    </div>
</form>
@endsection
