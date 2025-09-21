<div class="bg-white rounded shadow p-4">
  <h3 class="font-semibold mb-3">Geciken Faturalar</h3>
  <div class="divide-y">
    @forelse($overdueInvoices as $inv)
      <div class="py-2 flex items-center justify-between">
        <div>
          <div class="font-medium">
            {{ $inv->customer ? ($inv->customer->customer_type === 'corporate' ? $inv->customer->name : $inv->customer->name . ' ' . ($inv->customer->surname ?? '')) : 'Müşteri' }}
          </div>
          <div class="text-xs text-gray-500">Vade: {{ optional($inv->due_date)->format('Y-m-d') }}</div>
        </div>
        <div class="text-right">
          <div class="font-semibold">₺{{ number_format($inv->total, 2, ',', '.') }}</div>
          <a class="text-indigo-600 text-xs underline" href="{{ route('invoices.edit', $inv) }}">Aç</a>
        </div>
      </div>
    @empty
      <div class="py-4 text-sm text-gray-500">Geciken fatura yok.</div>
    @endforelse
  </div>
</div>


