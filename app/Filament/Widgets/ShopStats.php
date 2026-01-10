<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class ShopStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $totalProducts = Product::count();

        $ordersToday = Order::whereDate('created_at', $today)->count();

        $paidAmount = Order::where('status', 'paid')->sum('total');

        $usersCount = User::count();

        return [
            Stat::make('Товаров', $totalProducts),

            Stat::make('Заказов сегодня', $ordersToday),

            Stat::make('Оплачено всего', number_format($paidAmount, 2, ',', ' ') . ' ₽'),

            Stat::make('Пользователей', $usersCount),
        ];
    }
}
