<?php

namespace App\Models;

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
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }


    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
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
            TextInput::make('url')
                ->maxLength(255),
            Textarea::make('description')
                ->columnSpanFull(),
            TextInput::make('logo')
                ->maxLength(255),
            TextInput::make('primary_hex_color')
                ->maxLength(255),
            Toggle::make('is_active')
                ->required(),
            Toggle::make('is_visible')
                ->required(),
            Textarea::make('notes')
                ->columnSpanFull(),
        ];
    }
} // end class Brand
