<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static int $globalSearchResultsLimit = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return ['order_number', 'customer.name', 'status',];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer' => $record->customer->name ?? 'N/A',
            'Status' => $record->status,
            'Total Amount' => $record->total_amount,
            'Placed At' => $record->placed_at?->format('Y-m-d H:i:s'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])->with(['customer']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Order::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money()
                    ]),

                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('shipping_price')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_address')
                    ->searchable(),

                Tables\Columns\TextColumn::make('billing_address')
                    ->searchable(),

                Tables\Columns\TextColumn::make('placed_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
