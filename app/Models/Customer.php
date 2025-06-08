<?php

namespace App\Models;

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



} // end class Customer
