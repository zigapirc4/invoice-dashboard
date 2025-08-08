<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Client;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use App\Filament\Widgets\ClientStatsOverview;
use App\Filament\Widgets\RevenueByClientChart;
use App\Filament\Widgets\MonthlyRevenueChart;
use Illuminate\Contracts\View\View;

class Analytics extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.analytics';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'date_from' => now()->subYear()->startOfMonth(),
            'date_to' => now(),
            'client_id' => null,
            'status' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date_from')
                    ->label('From Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->updateFilters()),
                DatePicker::make('date_to')
                    ->label('To Date')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn () => $this->updateFilters()),
                Select::make('client_id')
                    ->label('Client')
                    ->options(Client::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('All Clients')
                    ->live()
                    ->afterStateUpdated(fn () => $this->updateFilters()),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->placeholder('All Statuses')
                    ->live()
                    ->afterStateUpdated(fn () => $this->updateFilters()),
            ])
            ->columns(4)
            ->statePath('data');
    }

    protected function updateFilters(): void
    {
        $this->dispatch('filter-updated', data: $this->data);
    }

    public function getWidgets(): array
    {
        return [
            ClientStatsOverview::class,
            RevenueByClientChart::class,
            MonthlyRevenueChart::class,
        ];
    }
} 