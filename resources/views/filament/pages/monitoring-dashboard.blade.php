<x-filament::page>
    {{-- MONITORING REAL TIME --}}
    <h2 class="text-xl font-bold mb-4">Monitoring Real Time</h2>
    <div class="grid grid-cols-3 gap-4">
        @foreach($this->getMonitoringRealTime() as $status => $items)
            <div class="bg-white shadow rounded p-3">
                <h3 class="font-semibold text-center">{{ ucfirst($status) }}</h3>
                <ul>
                    @forelse($items as $item)
                        <li>{{ $item->nomor_antrian }} - {{ $item->layanan }}</li>
                    @empty
                        <li class="text-gray-500 italic">Tidak ada data</li>
                    @endforelse
                </ul>
            </div>
        @endforeach
    </div>

    {{-- REKAP PER HARI --}}
    <h2 class="text-xl font-bold my-6">Rekap Per Hari</h2>

    <div class="flex items-center space-x-4 mb-4">
        <div>
            <label class="block text-sm font-medium">Dari Tanggal</label>
            <input type="date" wire:model="from" class="border p-2 rounded">
        </div>
        <div>
            <label class="block text-sm font-medium">Sampai Tanggal</label>
            <input type="date" wire:model="to" class="border p-2 rounded">
        </div>
    </div>
    
    <table class="w-full border mt-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Layanan</th>
                <th class="border p-2">Jumlah Pemohon</th>
            </tr>
        </thead>
        <tbody>
            @foreach($this->getRekapHarian() as $rekap)
                <tr>
                    <td class="border p-2">{{ $rekap->name }}</td>
                    <td class="border p-2">{{ (int) $rekap->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <div class="mt-4">
        <x-filament::button wire:click="exportExcel">Export Excel</x-filament::button>
    </div>
</x-filament::page>
