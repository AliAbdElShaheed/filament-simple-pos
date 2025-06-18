<?php

namespace App\Models;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable properties for the model
    protected $fillable = [
        'order_number',
        'customer_id',
        'total_amount',
        'status',
        'payment_status',
        'shipping_price',
        'shipping_address',
        'billing_address',
        'placed_at',
        'delivered_at',
        'cancelled_at',
        'notes',
    ];


    // Casts

    public static function getFormSchema(): array
    {
        return [
            /*TextInput::make('order_number')
                ->required()
                ->maxLength(255),
            Select::make('customer_id')
                ->relationship('customer', 'name')
                ->required(),
            TextInput::make('total_amount')
                ->required()
                ->numeric()
                ->default(0.00),
            TextInput::make('status')
                ->required()
                ->maxLength(255)
                ->default('pending'),
            TextInput::make('payment_status')
                ->required()
                ->maxLength(255)
                ->default('unpaid'),
            TextInput::make('shipping_price')
                ->required()
                ->numeric()
                ->default(0.00),
            TextInput::make('shipping_address')
                ->maxLength(255),
            TextInput::make('billing_address')
                ->maxLength(255),
            DateTimePicker::make('placed_at'),
            DateTimePicker::make('delivered_at'),
            DateTimePicker::make('cancelled_at'),
            Textarea::make('notes')
                ->columnSpanFull(),*/

            Wizard::make([
                Wizard\Step::make('Order Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            //->default(fn($record): string => 'ORD-' . now()->format('Ymd') . '-' . str_pad(optional($record)->id ?? 1, 5, '0', STR_PAD_LEFT))
                            ->default(fn($record): string => 'ORD-' . now()->format('ymdHis') . '-' . now()->format('v'))
                            ->disabled()
                            ->dehydrated()
                            ->maxLength(20),

                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),


                        Select::make('status')
                            ->enum(OrderStatus::class)
                            ->options(OrderStatus::class)
                            ->native(false)
                            ->default(OrderStatus::class::PENDING)
                            ->required(),

                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->enum(OrderPaymentStatus::class)
                            ->options(OrderPaymentStatus::class)
                            ->native(false)
                            ->default(OrderPaymentStatus::class::UNPAID)
                            ->required(),


                        MarkdownEditor::make('notes')
                            ->label('Order Notes')
                            ->helperText('You can add any notes or special instructions for this order.')
                            ->columnSpanFull()
                            ->minHeight(50)
                            //->maxHeight(300)
                            ->maxLength(500),
                    ]), // end step 1

                Wizard\Step::make('Order Items')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('Items')
                            ->columns(4)
                            //->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::query()->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),

                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->default(0.00)
                                    ->minValue(0.00)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->helperText('Price per unit of the product.'),

                                TextInput::make('total_units_price')
                                    ->numeric()
                                    ->default(0.00)
                                    ->minValue(0.00)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->helperText('Total price for the quantity of this product.'),
                            ])
                    ]), // end step 2

            ])->columnSpanFull(), // end wizard

        ]; // end getFormSchema return

    } // end getFormSchema function


    // Relationships

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }


    // Functions

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'customer_id' => 'integer',
            'placed_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'status' => OrderStatus::class,
            'payment_status' => OrderPaymentStatus::class,
            'shipping_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }
} // end class Order
