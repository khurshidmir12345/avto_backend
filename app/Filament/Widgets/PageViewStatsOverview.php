<?php

namespace App\Filament\Widgets;

use App\Models\PageView;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PageViewStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayTotal = PageView::where('view_date', $today)->count();
        $yesterdayTotal = PageView::where('view_date', $yesterday)->count();

        $todayHome = PageView::where('view_date', $today)->where('page', 'home')->count();
        $yesterdayHome = PageView::where('view_date', $yesterday)->where('page', 'home')->count();

        $todayListings = PageView::where('view_date', $today)->where('page', 'listings')->count();
        $yesterdayListings = PageView::where('view_date', $yesterday)->where('page', 'listings')->count();

        $todayUniqueDevices = PageView::where('view_date', $today)
            ->whereNotNull('device_id')
            ->distinct('device_id')
            ->count('device_id');

        $yesterdayUniqueDevices = PageView::where('view_date', $yesterday)
            ->whereNotNull('device_id')
            ->distinct('device_id')
            ->count('device_id');

        $last7daysChart = PageView::query()
            ->select(DB::raw('view_date, COUNT(*) as total'))
            ->where('view_date', '>=', $today->copy()->subDays(6))
            ->groupBy('view_date')
            ->orderBy('view_date')
            ->pluck('total')
            ->toArray();

        return [
            Stat::make('Bugungi ko\'rishlar', number_format($todayTotal))
                ->description($this->buildDiffDescription($todayTotal, $yesterdayTotal))
                ->descriptionIcon($todayTotal >= $yesterdayTotal ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayTotal >= $yesterdayTotal ? 'success' : 'danger')
                ->chart($last7daysChart),

            Stat::make('Asosiy sahifa (bugun)', number_format($todayHome))
                ->description($this->buildDiffDescription($todayHome, $yesterdayHome))
                ->descriptionIcon($todayHome >= $yesterdayHome ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayHome >= $yesterdayHome ? 'success' : 'danger'),

            Stat::make('E\'lonlar sahifasi (bugun)', number_format($todayListings))
                ->description($this->buildDiffDescription($todayListings, $yesterdayListings))
                ->descriptionIcon($todayListings >= $yesterdayListings ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayListings >= $yesterdayListings ? 'success' : 'danger'),

            Stat::make('Noyob qurilmalar (bugun)', number_format($todayUniqueDevices))
                ->description($this->buildDiffDescription($todayUniqueDevices, $yesterdayUniqueDevices))
                ->descriptionIcon($todayUniqueDevices >= $yesterdayUniqueDevices ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayUniqueDevices >= $yesterdayUniqueDevices ? 'success' : 'danger'),
        ];
    }

    private function buildDiffDescription(int $today, int $yesterday): string
    {
        if ($yesterday === 0) {
            return $today > 0 ? "+{$today} kechaga nisbatan" : 'Kecha ham 0 edi';
        }

        $diff = $today - $yesterday;
        $percent = round(abs($diff) / $yesterday * 100);
        $sign = $diff >= 0 ? '+' : '-';

        return "{$sign}{$percent}% kechaga nisbatan";
    }
}
