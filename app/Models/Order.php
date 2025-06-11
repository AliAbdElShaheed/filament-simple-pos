<?php

namespace App\Models;

use App\Enums\OrderPaymentStatus;
use App\Enums\OrderStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('order_number')
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
                ->columnSpanFull(),
        ];
    }
} // end class Order
