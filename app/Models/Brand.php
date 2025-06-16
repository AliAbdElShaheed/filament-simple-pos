<?php

namespace App\Models;

use App\Enums\ProductType;
use Filament\Forms\Components\ColorPicker;
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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;

    // Fillable properties for the model
    protected $fillable = [
        'name',
        'slug',
        'url',
        'description',
        'logo',
        'primary_hex_color',
        'is_active',
        'is_visible',
        'notes',
    ];


    // Casts

    public static function getFormSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    Section::make('Brand')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(Brand::class, 'name', ignoreRecord: true)
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
                                ->unique(Brand::class, 'slug', ignoreRecord: true)
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('url')
                                ->label('Website URL')
                                ->url()
                                ->required()
                                ->prefixIcon('heroicon-o-globe-alt')
                                ->maxLength(255)
                                ->placeholder('https://example.com')
                                ->columnSpanFull(),

                            MarkdownEditor::make('description')
                                ->maxHeight('100px')
                                //->minHeight(100)
                                ->columnSpanFull(),
                        ]),
                ]),
            Group::make()
                ->schema([

                    Section::make('Brand Logo & Color')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            FileUpload::make('logo')
                                ->label('Logo Image')
                                ->image()
                                ->required()
                                ->maxSize(1024) // 1MB
                                ->disk('public') // Ensure you have a disk configured in config/filesystems.php
                                ->directory('brands/logos')
                                ->preserveFilenames()
                                ->columnSpanFull(),

                            ColorPicker::make('primary_hex_color')
                                ->label('Primary Color')
                                ->required()
                                ->placeholder('#FFFFFF'),
                        ]),

                    Section::make('Brand Status')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Activity Status')
                                ->required()
                                ->default(false),

                            Toggle::make('is_visible')
                                ->label('Visibility')
                                ->required()
                                ->default(false),
                        ]),
                ]),

            Textarea::make('notes')
                ->columnSpanFull(),
        ];
    }


    // Relationships

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Functions

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
} // end class Brand
