<?php

namespace App\Http\Controllers;

use App\Models\BillOfLading;
use App\Models\Transfer;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

class BillOfLadingController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function index()
    {
        $bills = BillOfLading::with('transfers')->latest()->paginate(15);
        $chainStatus = $this->blockchain->verifyChain();

        return view('bl.index', compact('bills', 'chainStatus'));
    }

    public function create()
    {
        return view('bl.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipper' => 'required|string|max:255',
            'consignee' => 'required|string|max:255',
            'carrier' => 'required|string|max:255',
            'vessel_name' => 'required|string|max:255',
            'voyage_no' => 'required|string|max:50',
            'port_of_loading' => 'required|string|max:255',
            'port_of_discharge' => 'required|string|max:255',
            'cargo_description' => 'required|string',
            'quantity' => 'required|string',
            'weight' => 'required|string',
            'current_owner' => 'required|string|max:255',
        ]);

        $bl = new BillOfLading($validated);
        $bl->data_hash = $bl->generateDataHash();
        $bl->save();

        $result = $this->blockchain->issueBill($bl);

        $bl->update([
            'blockchain_tx_hash' => $result['tx_hash'] ?? null,
            'block_id' => $result['block_id'] ?? null,
        ]);

        return redirect()->route('bl.show', $bl)
            ->with('success', 'eBL berhasil diterbitkan dan dicatat di blockchain!');
    }

    public function show(BillOfLading $billOfLading)
    {
        $history = $this->blockchain->getHistory($billOfLading->bl_number);
        $integrity = $billOfLading->verifyIntegrity();

        return view('bl.show', compact('billOfLading', 'history', 'integrity'));
    }

    public function transferForm(BillOfLading $billOfLading)
    {
        return view('bl.transfer', compact('billOfLading'));
    }

    public function transfer(Request $request, BillOfLading $billOfLading)
    {
        $validated = $request->validate([
            'to_owner' => 'required|string|max:255',
            'reason' => 'nullable|string|max:255',
        ]);

        $from = $billOfLading->current_owner;

        $billOfLading->update([
            'current_owner' => $validated['to_owner'],
            'status' => 'transferred',
        ]);

        Transfer::create([
            'bill_of_lading_id' => $billOfLading->id,
            'from_owner' => $from,
            'to_owner' => $validated['to_owner'],
            'reason' => $validated['reason'] ?? 'transfer',
            'transferred_at' => now(),
        ]);

        $result = $this->blockchain->transferOwnership(
            $billOfLading,
            $from,
            $validated['to_owner'],
            $validated['reason'] ?? 'transfer'
        );

        return redirect()->route('bl.show', $billOfLading)
            ->with('success', 'Ownership berhasil ditransfer dan dicatat di blockchain!');
    }

    public function chain()
    {
        $blocks = \App\Models\Block::orderBy('index', 'desc')->paginate(20);
        $status = $this->blockchain->verifyChain();

        return view('bl.chain', compact('blocks', 'status'));
    }
}
