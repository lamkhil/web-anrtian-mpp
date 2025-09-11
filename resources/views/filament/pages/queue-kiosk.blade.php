<div class="py-6">
    <!-- Instructions -->
    <div class="text-center my-6">
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-gray-200/50 max-w-3xl mx-auto">
            <h4 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Petunjuk Penggunaan</h4>
            <p class="text-gray-600 text-base md:text-lg leading-relaxed">
                Pilih zona (counter) → pilih layanan → cetak struk/barcode.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="relative flex-1 flex justify-center px-6 lg:px-8">
        <div class="w-full max-w-6xl">

            {{-- STEP 1: Pilih Counter --}}
            @if(!$selectedCounter)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
                    @foreach($this->counters as $counter)
                        <button wire:click="selectCounter({{ $counter->id }})"
                            class="service-card bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:-translate-y-2 hover:scale-105 transition">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Zona {{ $counter->id }}</h3>
                            <p class="text-gray-600">Klik untuk lihat layanan</p>
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- STEP 2: Pilih Layanan --}}
            @if($selectedCounter && !$selectedService)
                <button wire:click="resetSelection" class="mb-4 px-4 py-2 bg-gray-200 rounded">← Kembali ke Counter</button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
                    @foreach($selectedCounter->service as $service)
                        <button wire:click="selectService({{ $service->id }})"
                            class="service-card bg-white rounded-2xl shadow-lg p-8 border border-gray-100 hover:-translate-y-2 hover:scale-105 transition">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $service->name }}</h3>
                            <p class="text-gray-600">Klik untuk memilih</p>
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- STEP 3: Pilih Jenis Cetak --}}
            @if($selectedService)
                <button wire:click="selectCounter({{ $selectedCounter->id }})" class="mb-4 px-4 py-2 bg-gray-200 rounded">← Kembali ke Layanan</button>
                <div class="flex justify-center space-x-6">
                    <button wire:click="printStruk({{ $selectedService->id }})"
                        class="px-8 py-4 bg-blue-600 text-white rounded-xl shadow-lg">Cetak Struk</button>
                    <button wire:click="printBarcode({{ $selectedService->id }})"
                        class="px-8 py-4 bg-green-600 text-white rounded-xl shadow-lg">Cetak Barcode</button>
                </div>
            @endif
        </div>
    </main>
</div>

@push('styles')
<style>
    .service-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-12px) scale(1.03);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
    }

    .service-card:active {
        transform: translateY(-8px) scale(1.01);
    }

    .service-icon {
        transition: all 0.5s ease;
    }

    .status-indicator {
        width: 14px;
        height: 14px;
        background: #10b981;
        border-radius: 50%;
        position: relative;
    }

    .status-indicator::before {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }

        70% {
            transform: scale(1);
            box-shadow: 0 0 0 15px rgba(16, 185, 129, 0);
        }

        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .clock-display {
        font-family: 'Inter', monospace;
        font-weight: 600;
        letter-spacing: 0.1em;
    }

    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.3);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .service-card:active .ripple-effect {
        animation: ripple 0.6s linear;
    }

    /* Kiosk Mode Styles */
    body {
        overflow: hidden;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    /* Floating Animation */
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    /* Responsive Design for Kiosk */
    @media (max-height: 768px) {
        .text-6xl {
            font-size: 3rem;
        }

        .text-7xl {
            font-size: 4rem;
        }

        .p-12 {
            padding: 2rem;
        }

        .mb-16 {
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        const connectButton = document.getElementById('connect-button');

        if (connectButton) {
            connectButton.addEventListener('click', async () => {
                window.connectedPrinter = await getPrinter()
            })
        }

        Livewire.on("print-start", async (text) => {
            await printThermal(text)
        })
    })
</script>
@endpush