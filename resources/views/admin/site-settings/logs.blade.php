<x-app-layout>
  <div class="max-w-7xl mx-auto p-6 space-y-8">
    <!-- Başlık -->
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl p-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Sistem Logları</h1>
          <p class="text-orange-100 mt-1">Laravel sistem loglarını görüntüleyin</p>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold">{{ count($logs) }}</div>
          <div class="text-orange-100">Log Satırı</div>
        </div>
      </div>
    </div>

    <!-- Log Kontrolleri -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Log Yönetimi</h2>
        <div class="flex space-x-2">
          <button onclick="refreshLogs()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Yenile
          </button>
          <button onclick="downloadLogs()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            İndir
          </button>
          <button onclick="clearLogs()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Temizle
          </button>
        </div>
      </div>

      <!-- Log Filtreleri -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Log Seviyesi</label>
          <select id="logLevel" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            <option value="">Tümü</option>
            <option value="ERROR">Hata</option>
            <option value="WARNING">Uyarı</option>
            <option value="INFO">Bilgi</option>
            <option value="DEBUG">Debug</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tarih</label>
          <input type="date" id="logDate" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Arama</label>
          <input type="text" id="logSearch" placeholder="Log içinde ara..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div class="flex items-end">
          <button onclick="filterLogs()" class="w-full bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition-colors">
            Filtrele
          </button>
        </div>
      </div>
    </div>

    <!-- Log Listesi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-4">Log Kayıtları</h2>
      
      <div class="overflow-x-auto">
        <div id="logContainer" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm max-h-96 overflow-y-auto">
          @if(count($logs) > 0)
            @foreach($logs as $log)
              <div class="log-entry mb-1" data-level="{{ \App\Http\Controllers\Admin\SiteSettingsController::getLogLevel($log) }}" data-date="{{ \App\Http\Controllers\Admin\SiteSettingsController::getLogDate($log) }}" data-content="{{ $log }}">
                <span class="text-gray-400">{{ $log }}</span>
              </div>
            @endforeach
          @else
            <div class="text-gray-500">Henüz log kaydı bulunmuyor.</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <script>
    function refreshLogs() {
      location.reload();
    }

    function downloadLogs() {
      const logContent = document.getElementById('logContainer').innerText;
      const blob = new Blob([logContent], { type: 'text/plain' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'laravel-logs.txt';
      a.click();
      URL.revokeObjectURL(url);
    }

    function clearLogs() {
      if (confirm('Tüm logları temizlemek istediğinizden emin misiniz?')) {
        fetch('{{ route("admin.site-settings.clear-logs") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
          },
        }).then(() => {
          location.reload();
        });
      }
    }

    function filterLogs() {
      const level = document.getElementById('logLevel').value;
      const date = document.getElementById('logDate').value;
      const search = document.getElementById('logSearch').value.toLowerCase();

      const logEntries = document.querySelectorAll('.log-entry');
      
      logEntries.forEach(entry => {
        let show = true;
        
        // Level filter
        if (level && entry.dataset.level !== level) {
          show = false;
        }
        
        // Date filter
        if (date && entry.dataset.date && !entry.dataset.date.includes(date)) {
          show = false;
        }
        
        // Search filter
        if (search && !entry.dataset.content.toLowerCase().includes(search)) {
          show = false;
        }
        
        entry.style.display = show ? 'block' : 'none';
      });
    }

    // Log seviyesi belirleme
    function getLogLevel(logText) {
      if (logText.includes('ERROR')) return 'ERROR';
      if (logText.includes('WARNING')) return 'WARNING';
      if (logText.includes('INFO')) return 'INFO';
      if (logText.includes('DEBUG')) return 'DEBUG';
      return 'INFO';
    }

    // Log tarihi çıkarma
    function getLogDate(logText) {
      const match = logText.match(/\[(\d{4}-\d{2}-\d{2})/);
      return match ? match[1] : '';
    }

    // Log girişlerini renklendir
    document.addEventListener('DOMContentLoaded', function() {
      const logEntries = document.querySelectorAll('.log-entry');
      logEntries.forEach(entry => {
        const level = getLogLevel(entry.textContent);
        const date = getLogDate(entry.textContent);
        
        entry.dataset.level = level;
        entry.dataset.date = date;
        entry.dataset.content = entry.textContent;
        
        // Renk kodlaması
        switch(level) {
          case 'ERROR':
            entry.style.color = '#ef4444';
            break;
          case 'WARNING':
            entry.style.color = '#f59e0b';
            break;
          case 'INFO':
            entry.style.color = '#10b981';
            break;
          case 'DEBUG':
            entry.style.color = '#6b7280';
            break;
        }
      });
    });
  </script>
</x-app-layout>
