<x-filament-panels::page.simple>
    <div class="text-center w-full">
        {{ date('d M Y') }}
        <img class="w-full text-center" src="{{ asset('logo.png') }}" alt="LOGO DPMPTSP">
    </div>
    {{$this->table}}
</x-filament-panels::page.simple>
