<?php

namespace App\Models;

use App\Enums\ProductType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
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
    protected $casts = [
        'slug' => 'string',
        'brand_id' => 'integer',
        'sku' => 'string',
        'bar_code' => 'string',
        'description' => 'string',
        'image' => 'string',
        'type' => ProductType::class,
        //'unit_type' => UnitType::class,

        'purchase_price' => 'float',
        'sale_price' => 'float',
        'product_profit' => 'float',

        'quantity' => 'integer',
        'minimum_quantity' => 'integer',

        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];


    // Functions
    public static function getFormSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    Section::make('Product')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(Product::class, 'name', ignoreRecord: true)
                                ->live(onBlur: true)
                                ->maxLength(255)
                                ->afterStateUpdated(function (string $operation, $state, $set) {
                                    // If the operation is not 'create', we don't need to generate a slug
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    // Generate slug from name
                                    $slug = str($state)->slug();
                                    $set('slug', $slug);
                                }),

                            TextInput::make('slug')
                                ->required()
                                ->unique(Product::class, 'slug', ignoreRecord: true)
                                ->disabled()
                                ->dehydrated(),

                            Select::make('brand_id')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('type')
                                ->enum(ProductType::class)
                                ->options(ProductType::class)
                                ->required(),

                            TextInput::make('sku')
                                ->label('SKU (Stock Keeping Unit)')
                                ->unique()
                                ->required(),

                            TextInput::make('bar_code')
                                ->maxLength(255),

                            MarkdownEditor::make('description')
                                ->maxHeight('100px')
                                //->minHeight(100)
                                ->columnSpanFull(),

                            FileUpload::make('image')
                                ->directory('products-images')
                                //->preserveFilenames()
                                ->image()
                                ->imageEditor()
                                ->columnSpanFull(),
                        ]),
                ]),

            Group::make()
                ->schema([
                    Section::make('Pricing')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            TextInput::make('purchase_price')
                                ->required()
                                ->numeric()
                                ->rules(['min:0', 'regex:/^\d+(\.\d{1,3})?$/'])
                                ->step(0.001)
                                ->reactive()
                                //->afterStateUpdated(fn($set, $get) => $set('product_profit', ($get('sale_price') ?? 0) - ($get('purchase_price') ?? 0))),
                                ->afterStateUpdated(fn($set, $get) => $set('product_profit',
                                    floatval($get('sale_price') ?: 0) - floatval($get('purchase_price') ?: 0)
                                )),

                            TextInput::make('sale_price')
                                ->required()
                                ->numeric()
                                //->rules(['min:0.01', 'regex:/^\d+(\.\d{1,3})?$/', 'gt:purchase_price'])
                                ->rules(['min:0.01', 'regex:/^\d+(\.\d{1,3})?$/'])
                                ->step(0.001)
                                ->reactive()
                                //->afterStateUpdated(fn($set, $get) => $set('product_profit', ($get('sale_price') ?? 0) - ($get('purchase_price') ?? 0)))
                                ->afterStateUpdated(fn($set, $get) => $set('product_profit',
                                    floatval($get('sale_price') ?: 0) - floatval($get('purchase_price') ?: 0)
                                )),

                            TextInput::make('product_profit')
                                ->required()
                                ->numeric()
                                ->rules(['min:0.01', 'regex:/^\d+(\.\d{1,3})?$/'])
                                ->disabled()
                                ->dehydrated(),

                        ]),

                    Section::make('Inventory')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            TextInput::make('quantity')
                                ->required()
                                //->rules(['min:0', 'max:1000', 'integer'])
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(1000)
                                ->default(0),

                            TextInput::make('minimum_quantity')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                //->rules(['min:0', 'integer'])
                                ->default(0),
                        ]),

                    Section::make('Visibility')
                        ->columns(3)
                        ->collapsed(false)
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Activity')
                                ->helperText('Is this product active?')
                                ->required(),
                            Toggle::make('is_visible')
                                ->label('Visibility')
                                ->helperText('Enable or Disable product visibility to customers')
                                ->required(),
                            Toggle::make('is_featured')
                                ->label('Featured')
                                ->helperText('Enable or Disable product featured status')
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Published At')
                                ->default(now())
                                ->columnSpanFull(),
                        ]),
                ]),

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

    /*protected function casts(): array
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
    }*/


} // end class Product
