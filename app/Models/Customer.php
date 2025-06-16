<?php

namespace App\Models;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
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

    public static function getFormSchema(): array
    {
        return [
            Section::make('Customer Details')
                ->description('Please fill in the customer details.')
                ->columns(3)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(75),

                    TextInput::make('email')
                        ->label('Email Address')
                        ->email()
                        ->prefixIcon('heroicon-o-envelope')
                        ->required()
                        ->unique(Customer::class, 'email', ignoreRecord: true)
                        ->maxLength(255),

                    DatePicker::make('date_of_birth')
                        ->label('Date of Birth')
                        ->maxDate(now())
                        ->native(false)
                        ->placeholder('Select date of birth')
                        ->suffixIcon('heroicon-o-cake'),

                    TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(11),

                    TextInput::make('phone2')
                        ->tel()
                        ->maxLength(11),
                ]),


            Section::make('Contact Information')
                ->description('Please provide the contact details of the customer.')
                ->columns(4)
                ->schema([
                    TextInput::make('address')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    TextInput::make('address2')
                        ->label('Address Line 2')
                        ->maxLength(255)
                        ->columnSpan(2),


                    TextInput::make('country')
                        ->maxLength(100),
                    TextInput::make('state')
                        ->maxLength(100),
                    TextInput::make('city')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('postal_code')
                        ->label('Postal Code')
                        ->numeric()
                        ->maxLength(20),
                ]),

            Textarea::make('additional_info')
                ->label('Additional Information')
                ->rows(3)
                ->maxLength(500)
                ->placeholder('Any additional information about the customer')
                ->columnSpanFull(),


        ];
    }


    // Relationships

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }


    // Functions

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'date_of_birth' => 'date',
        ];
    }
} // end class Customer
