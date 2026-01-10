<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Models\Order;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                    ->label('Статус')
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->name])
                            ->toArray()
                    )
                    ->required(),

                TextInput::make('total')
                    ->label('Сумма')
                    ->numeric()
                    ->required(),

                Textarea::make('shipping_address_pretty')
                    ->label('Адрес доставки')
                    ->disabled()
                    ->dehydrated(false)
                    ->rows(4)
                    ->formatStateUsing(function ($record) {
                        if (! $record || ! is_array($record->shipping_address)) {
                            return null;
                        }

                        $addr = $record->shipping_address;

                        $lines = [
                            Arr::get($addr, 'name'),
                            Arr::get($addr, 'phone'),
                            trim(
                                (Arr::get($addr, 'city') ?? '') .
                                (Arr::get($addr, 'street') ? ', ' . Arr::get($addr, 'street') : '')
                            ),
                        ];

                        $lines = array_filter(
                            array_map(fn ($v) => $v !== null ? trim((string) $v) : null, $lines)
                        );

                        return $lines ? implode("\n", $lines) : null;
                    }),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('№')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Сумма')
                    ->money('rub', locale: 'ru_RU')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->name])
                            ->toArray()
                    ),

                SelectFilter::make('user')
                    ->label('Пользователь')
                    ->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Продажи';
    }

    public static function getNavigationLabel(): string
    {
        return 'Заказы';
    }

    public static function getModelLabel(): string
    {
        return 'заказ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'заказы';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            PaymentsRelationManager::class,
        ];
    }
}
