<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UniqueDevicesChart extends ChartWidget
{
    protected ?string $heading = 'Kunlik noyob qurilmalar';

    protected ?string $description = 'Har kuni nechta turli qurilma ilovani ochgan';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '320px';

    public ?string $filter = '30';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Oxirgi 7 kun',
            '14' => 'Oxirgi 14 kun',
            '30' => 'Oxirgi 30 kun',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 30);
        $startDate = Carbon::today()->subDays($days - 1);

        $uniqueDevices = PageView::query()
            ->select('view_date', DB::raw('COUNT(DISTINCT device_id) as total'))
            ->where('view_date', '>=', $startDate)
            ->whereNotNull('device_id')
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total', 'view_date');

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $labels[] = $date->format('d.m');
            $data[] = $uniqueDevices[$dateStr] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Noyob qurilmalar',
                    'data' => $data,
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.15)',
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
