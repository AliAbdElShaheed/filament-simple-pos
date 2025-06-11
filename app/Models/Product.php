<?php

namespace App\Models;

use App\Enums\ProductType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    // Fillable properties for the model
    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'sku',
        'bar_code',
        'description',
        'image',
        'type',
        //'unit_type',

        'purchase_price',
        'sale_price',
        'product_profit',

        'quantity',
        'minimum_quantity',

        'is_active',
        'is_visible',
        'is_featured',
        'published_at',
    ];



    protected $appends = [
        'image_url',
        'price_formatted',
        'purchase_price_formatted',
        'published_at_formatted',
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'brand_id' => 'integer',

            'sale_price' => 'decimal:3',
            'purchase_price' => 'decimal:3',
            'product_profit' => 'decimal:3',

            'quantity' => 'integer',
            'minimum_quantity' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'type' => ProductType::class,
        ];
    }




    // Relationships
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class);
    }



    // Accessors & Mutators
    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('storage/products/' . $this->image) : asset('images/default-product.png');
    }

    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->sale_price, 3, '.', ',');
    }

    public function getPurchasePriceFormattedAttribute(): string
    {
        return number_format($this->purchase_price, 3, '.', ',');
    }



    public function getPublishedAtFormattedAttribute(): string
    {
        return $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : '';
    }




    // Functions
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->required()
                ->maxLength(255),
            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->required(),
            TextInput::make('sku')
                ->label('SKU')
                ->required()
                ->maxLength(255),
            TextInput::make('bar_code')
                ->maxLength(255),
            Textarea::make('description')
                ->columnSpanFull(),
            FileUpload::make('image')
                ->image(),
            TextInput::make('type')
                ->required(),
            TextInput::make('purchase_price')
                ->required()
                ->numeric()
                ->default(0.000),
            TextInput::make('sale_price')
                ->required()
                ->numeric()
                ->default(10.000),
            TextInput::make('product_profit')
                ->required()
                ->numeric()
                ->default(10.000),
            TextInput::make('quantity')
                ->required()
                ->numeric()
                ->default(0),
            TextInput::make('minimum_quantity')
                ->required()
                ->numeric()
                ->default(0),
            Toggle::make('is_active')
                ->required(),
            Toggle::make('is_visible')
                ->required(),
            Toggle::make('is_featured')
                ->required(),
            DateTimePicker::make('published_at'),
        ];
    }
} // end class Product
