<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Invoice;
use Carbon\Carbon;
use Livewire\Attributes\On;

class MonthlyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';
    protected static ?int $sort = 4;

    public ?array $data = [];

    #[On('filter-updated')]
    public function updateFilters(array $data): void
    {
        $this->data = $data;
        $this->dispatch('chart-updated');
    }

    protected function getData(): array
    {
        $query = Invoice::query();
        
        if ($this->data) {
            $query = $this->applyFilters($query);
        }

        $data = $query->get()
            ->groupBy(function ($invoice) {
                return Carbon::parse($invoice->date)->format('Y-m');
            })
            ->map(function ($invoices) {
                return $invoices->sum('amount');
            })
            ->sortKeys();

        $labels = $data->keys()->map(function ($date) {
            return Carbon::createFromFormat('Y-m', $date)->format('M Y');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data->values()->toArray(),
                    'borderColor' => '#3B82F6',
                    'fill' => false,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
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