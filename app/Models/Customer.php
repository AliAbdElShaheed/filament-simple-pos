<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    // Fillable properties for the model
    protected $fillable = [
        'name',
        'email',
        'phone',
        'phone2',
        'address',
        'address2',
        'date_of_birth',
        'country',
        'state',
        'city',
        'postal_code',
        'additional_info',
    ];


    // Casts
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'date_of_birth' => 'date',
        ];
    }



    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    // Functions
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            TextInput::make('phone')
                ->tel()
                ->required()
                ->maxLength(255),
            TextInput::make('phone2')
                ->tel()
                ->maxLength(255),
            TextInput::make('address')
                ->required()
                ->maxLength(255),
            TextInput::make('address2')
                ->maxLength(255),
            DatePicker::make('date_of_birth'),
            TextInput::make('country')
                ->maxLength(255),
            TextInput::make('state')
                ->maxLength(255),
            TextInput::make('city')
                ->maxLength(255),
            TextInput::make('postal_code')
                ->maxLength(255),
            Textarea::make('additional_info')
                ->columnSpanFull(),
        ];
    }
} // end class Customer
