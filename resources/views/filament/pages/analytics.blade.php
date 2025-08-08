<x-filament-panels::page>
    <div class="p-4 bg-gray-800 rounded-lg shadow-sm mb-4">
        {{ $this->form }}
    </div>

    <div class="grid grid-cols-4 gap-4">
        <div class="!w-auto !max-w-none h-full">
            @livewire(\App\Filament\Widgets\ClientStatsOverview::class)
        </div>
        <div class="!w-auto !max-w-none h-full">
            @livewire(\App\Filament\Widgets\RevenueByClientChart::class)
        </div>
        <div class="!w-auto !max-w-none h-full">
            @livewire(\App\Filament\Widgets\InvoiceStatusChart::class)
        </div>
        <div class="!w-auto !max-w-none h-full">
            @livewire(\App\Filament\Widgets\MonthlyRevenueChart::class)
        </div>
    </div>
</x-filament-panels::page> 