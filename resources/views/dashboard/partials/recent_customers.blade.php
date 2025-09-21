<div class="bg-white rounded shadow p-4">
  <h3 class="font-semibold mb-3">Son Eklenen Müşteriler</h3>
  <ul class="divide-y">
    @forelse($recentCustomers as $c)
      <li class="py-2 flex items-center justify-between">
        <span class="font-medium">
          {{ $c->customer_type === 'corporate' ? $c->name : $c->name . ' ' . ($c->surname ?? '') }}
        </span>
        <a href="{{ route('customers.edit', $c) }}" class="text-indigo-600 text-xs underline">Düzenle</a>
      </li>
    @empty
      <li class="py-4 text-sm text-gray-500">Kayıt yok.</li>
    @endforelse
  </ul>
</div>


