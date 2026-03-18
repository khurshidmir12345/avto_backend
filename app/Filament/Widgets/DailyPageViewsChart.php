<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyPageViewsChart extends ChartWidget
{
    protected ?string $heading = 'Kunlik sahifa ko\'rishlar';

    protected ?string $description = 'Oxirgi 30 kun ichida sahifalar necha marta ko\'rilgani';

    protected static ?int $sort = 1;

    protected ?string $maxHeight = '320px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Oxirgi 7 kun',
            '14' => 'Oxirgi 14 kun',
            '30' => 'Oxirgi 30 kun',
            '90' => 'Oxirgi 90 kun',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);
        $startDate = Carbon::today()->subDays($days - 1);

        $homeViews = PageView::query()
            ->select(DB::raw('view_date, COUNT(*) as total'))
            ->where('page', 'home')
            ->where('view_date', '>=', $startDate)
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total', 'view_date');

        $listingsViews = PageView::query()
            ->select(DB::raw('view_date, COUNT(*) as total'))
            ->where('page', 'listings')
            ->where('view_date', '>=', $startDate)
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total', 'view_date');

        $labels = [];
        $homeData = [];
        $listingsData = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $labels[] = $date->format('d.m');
            $homeData[] = $homeViews[$dateStr] ?? 0;
            $listingsData[] = $listingsViews[$dateStr] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Asosiy sahifa',
                    'data' => $homeData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'E\'lonlar sahifasi',
                    'data' => $listingsData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
