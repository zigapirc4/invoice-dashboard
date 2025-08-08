<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Invoice;
use App\Models\Client;
use Livewire\Attributes\On;

class ClientStatsOverview extends StatsOverviewWidget
{
    public ?array $data = [];

    #[On('filter-updated')]
    public function updateFilters(array $data): void
    {
        $this->data = $data;
        $this->dispatch('stats-updated');
    }

    public function getStats(): array
    {
        $query = Invoice::query();
        
        if ($this->data) {
            $query = $this->applyFilters($query);
        }

        $totalRevenue = $query->sum('amount');
        $averageInvoiceAmount = $query->avg('amount');
        $totalInvoices = $query->count();
        $totalClients = Client::count();

        return [
            Stat::make('Total Revenue', '€' . number_format($totalRevenue, 2))
                ->description('Total revenue across all clients')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
            Stat::make('Average Invoice Amount', '€' . number_format($averageInvoiceAmount, 2))
                ->description('Average amount per invoice')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Total Invoices', $totalInvoices)
                ->description('Total number of invoices')
                ->descriptionIcon('heroicon-m-document')
                ->color('warning'),
            Stat::make('Total Clients', $totalClients)
                ->description('Total number of clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    protected function applyFilters($query)
    {
        if ($this->data['date_from']) {
            $query->where('date', '>=', $this->data['date_from']);
        }
        
        if ($this->data['date_to']) {
            $query->where('date', '<=', $this->data['date_to']);
        }
        
        if ($this->data['client_id']) {
            $query->where('client_id', $this->data['client_id']);
        }

        if ($this->data['status']) {
            $query->where('status', $this->data['status']);
        }

        return $query;
    }
} 