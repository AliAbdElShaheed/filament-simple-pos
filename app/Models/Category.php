<?php

namespace App\Models;

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

    public static function getFormSchema(): array
    {
        return [
            Group::make()
                ->schema([
                    Section::make('Category Details')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(Category::class, 'name', ignoreRecord: true)
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
                                ->unique(Category::class, 'slug', ignoreRecord: true)
                                ->disabled()
                                ->dehydrated(),

                            Select::make('parent_id')
                                ->label('Parent Category')
                                ->relationship('parent', 'name')
                                ->preload()
                                ->searchable()
                                ->placeholder('Select a parent category')
                                ->default(null)
                                ->columnSpanFull(),

                            MarkdownEditor::make('description')
                                ->maxHeight('100px')
                                ->columnSpanFull(),
                        ]),// end of Section

                ]), // end of Group

            Group::make()
                ->schema([
                    Section::make('Category Settings')
                        ->columns(2)
                        ->collapsed(false)
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Active')
                                ->helperText('Toggle to activate or deactivate this category.')
                                ->required()
                                ->default(false),

                            Toggle::make('is_visible')
                                ->label('Visibility')
                                ->helperText('Toggle to show or hide this category on the frontend.')
                                ->required()
                                ->default(false),


                            FileUpload::make('image')
                                ->label('Category Image')
                                ->image()
                                ->disk('public')
                                ->directory('categories')
                                ->visibility('public')
                                ->maxSize(1024) // 1MB
                                ->columnSpanFull(),
                        ]), // end of Section
                ]), // end of Group

            Textarea::make('notes')
                ->columnSpanFull(),

        ]; // end of return

    } // end of getFormSchema


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

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'parent_id' => 'integer',
            'is_active' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }
} // end class Category
