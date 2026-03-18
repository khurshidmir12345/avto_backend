<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PageViewsByPageChart extends ChartWidget
{
    protected ?string $heading = 'Sahifalar bo\'yicha taqsimot';

    protected ?string $description = 'Tanlangan davr ichida qaysi sahifa ko\'proq ko\'rilgan';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '320px';

    public ?string $filter = '7';

    protected function getFilters(): ?array
    {
        return [
            '1' => 'Bugun',
            '7' => 'Oxirgi 7 kun',
            '30' => 'Oxirgi 30 kun',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 7);
        $startDate = Carbon::today()->subDays($days - 1);

        $pageLabels = [
            'home' => 'Asosiy sahifa',
            'listings' => 'E\'lonlar',
            'elon_detail' => 'E\'lon tafsilot',
            'chat' => 'Chat',
            'profile' => 'Profil',
        ];

        $results = PageView::query()
            ->select('page', DB::raw('COUNT(*) as total'))
            ->where('view_date', '>=', $startDate)
            ->groupBy('page')
            ->orderByDesc('total')
            ->pluck('total', 'page');

        $labels = [];
        $data = [];
        $colors = [
            'home' => '#10b981',
            'listings' => '#3b82f6',
            'elon_detail' => '#f59e0b',
            'chat' => '#8b5cf6',
            'profile' => '#ef4444',
        ];
        $bgColors = [];

        foreach ($results as $page => $total) {
            $labels[] = $pageLabels[$page] ?? $page;
            $data[] = $total;
            $bgColors[] = $colors[$page] ?? '#6b7280';
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $bgColors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
