<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Invoice;
use Livewire\Attributes\On;

class InvoiceStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Invoices by Status';
    protected static ?int $sort = 3;

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

        $data = $query->select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Invoices',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#6B7280', // Draft
                        '#3B82F6', // Sent
                        '#10B981', // Paid
                        '#EF4444', // Overdue
                    ],
                ],
            ],
            'labels' => $data->pluck('status')->map(fn ($status) => ucfirst($status))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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