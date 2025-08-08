<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Client;
use Livewire\Attributes\On;

class RevenueByClientChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Client';
    protected static ?int $sort = 2;

    public ?array $data = [];

    #[On('filter-updated')]
    public function updateFilters(array $data): void
    {
        $this->data = $data;
        $this->dispatch('chart-updated');
    }

    protected function getData(): array
    {
        $query = Client::withSum('invoices', 'amount');
        
        if ($this->data) {
            $query = $this->applyFilters($query);
        }

        $data = $query->orderByDesc('invoices_sum_amount')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->pluck('invoices_sum_amount')->toArray(),
                    'backgroundColor' => '#10B981',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function applyFilters($query)
    {
        if ($this->data['date_from']) {
            $query->whereHas('invoices', function ($q) {
                $q->where('date', '>=', $this->data['date_from']);
            });
        }
        
        if ($this->data['date_to']) {
            $query->whereHas('invoices', function ($q) {
                $q->where('date', '<=', $this->data['date_to']);
            });
        }
        
        if ($this->data['client_id']) {
            $query->where('id', $this->data['client_id']);
        }

        if ($this->data['status']) {
            $query->whereHas('invoices', function ($q) {
                $q->where('status', $this->data['status']);
            });
        }

        return $query;
    }
} 