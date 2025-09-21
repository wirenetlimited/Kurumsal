<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerLedgerController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'entry_date' => ['required','date'],
            'notes' => ['nullable','string','max:255'],
            'debit' => ['nullable','numeric','min:0'],
            'credit' => ['nullable','numeric','min:0'],
        ]);

        $debit = (float)($validated['debit'] ?? 0);
        $credit = (float)($validated['credit'] ?? 0);

        if (($debit <= 0 && $credit <= 0) || ($debit > 0 && $credit > 0)) {
            return back()->withErrors(['debit' => 'Borç veya Alacak alanından yalnızca biri ve 0 dan büyük olmalı.'])->withInput();
        }

        // Mevcut bakiye
        $currentBalance = (float) DB::table('ledger_entries')
            ->where('customer_id', $customer->id)
            ->selectRaw('COALESCE(SUM(debit),0) - COALESCE(SUM(credit),0) as b')
            ->value('b');

        $newBalance = $currentBalance + $debit - $credit;

        LedgerEntry::create([
            'customer_id' => $customer->id,
            // nullable alanlar - manuel kayıt
            'related_type' => null,
            'related_id' => null,
            'entry_date' => $validated['entry_date'],
            'debit' => $debit,
            'credit' => $credit,
            'balance_after' => $newBalance,
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('status', 'Cari hareket eklendi.');
    }

    public function destroy(LedgerEntry $entry)
    {
        // Sadece manuel kayıtlar silinebilir (fatura/ödeme bağlı olmayan)
        if ($entry->related_type || $entry->related_id) {
            return back()->withErrors(['delete' => 'Fatura/Ödeme kaynaklı kayıtlar silinemez.']);
        }

        $entry->delete();

        return back()->with('status', 'Cari hareket silindi.');
    }
}


