<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Цена')
                    ->numeric()
                    ->required()
                    ->prefix('₽'),

                TextInput::make('stock_quantity')
                    ->label('Количество на складе')
                    ->numeric()
                    ->required()
                    ->default(0),

                FileUpload::make('image_url')
                    ->label('Изображение')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(2048)
                    ->nullable(),
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
                    ->sortable()
                    ->label('ID'),

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->label('Цена')
                    ->money('rub', locale: 'ru_RU')
                    ->sortable(),

                TextColumn::make('stock_quantity')
                    ->label('Остаток')
                    ->sortable(),

                ImageColumn::make('image_url')
                    ->label('Изображение')
                    ->disk('public')
                    ->width(60)
                    ->height(60)
                    ->square(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Категория')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'товар';
    }

    public static function getPluralModelLabel(): string
    {
        return 'товары';
    }

    public static function getNavigationLabel(): string
    {
        return 'Товары';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Каталог';
    }
}
