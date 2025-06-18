<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';


    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'bar_code', 'sale_price'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
//            /'brand' => $record->brand->name ?? 'N/A',
            'Bar Code' => $record->bar_code,
            'Type' => $record->type,
            'Sale Price' => $record->sale_price,
            'Quantity' => $record->quantity,
        ];
    }

    /*public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with(['brand'])
            ->where('is_active', true);
    }*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Product::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->circular()
                    //->size(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('bar_code')
                    ->label('Bar Code')
                    ->limit(7)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(35)
                    ->html()
                    ->markdown()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('purchase_price')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sale_price')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('product_profit')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('minimum_quantity')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Activation')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Is Active')
                    ->placeholder('All')
                    ->trueLabel('Active Products')
                    ->falseLabel('Inactive Products')
                    ->native(false),

                TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->placeholder('All')
                    ->trueLabel('Visible Products')
                    ->falseLabel('Hidden Products'),

                TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->trueLabel('Featured Products')
                    ->falseLabel('Non-Featured Products')
                    ->Native(false),

                SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->label('Brand')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'view' => Pages\ViewProduct::route('/{record}'),
            //'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
