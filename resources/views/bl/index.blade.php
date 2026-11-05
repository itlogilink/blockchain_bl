@extends('layouts.app')

@section('title', 'Daftar Electronic Bill of Lading')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Electronic Bill of Lading</h1>
    <a href="{{ route('bl.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Issue eBL Baru</a>
</div>

<div class="mb-4 p-3 rounded {{ $chainStatus['valid'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
    Chain Status: {{ $chainStatus['valid'] ? 'VALID' : 'INVALID' }} 
    ({{ $chainStatus['total_blocks'] }} blocks)
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">BL Number</th>
                <th class="px-4 py-2 text-left">Shipper</th>
                <th class="px-4 py-2 text-left">Consignee</th>
                <th class="px-4 py-2 text-left">Current Owner</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bills as $bl)
            <tr class="border-t">
                <td class="px-4 py-2 font-mono">{{ $bl->bl_number }}</td>
                <td class="px-4 py-2">{{ $bl->shipper }}</td>
                <td class="px-4 py-2">{{ $bl->consignee }}</td>
                <td class="px-4 py-2">{{ $bl->current_owner }}</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 text-xs rounded {{ $bl->status == 'issued' ? 'bg-blue-100' : 'bg-yellow-100' }}">
                        {{ $bl->status }}
                    </span>
                </td>
                <td class="px-4 py-2 text-center">
                    <a href="{{ route('bl.show', $bl) }}" class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada eBL. <a href="{{ route('bl.create') }}" class="text-blue-600">Issue yang pertama</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $bills->links() }}
</div>
@endsection
