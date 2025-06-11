<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    // Fillable properties for the model
    protected $fillable = [
        'name',
        'slug',
        'image',
        'parent_id',
        'description',
        'is_active',
        'is_visible',
        'notes',
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'parent_id' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }


    // Relationships
    public function children(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
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
            FileUpload::make('image')
                ->image(),
            Select::make('parent_id')
                ->relationship('parent', 'name'),
            Textarea::make('description')
                ->columnSpanFull(),
            Toggle::make('is_active')
                ->required(),
            Toggle::make('is_visible')
                ->required(),
            Textarea::make('notes')
                ->columnSpanFull(),
        ];
    }
} // end class Category
