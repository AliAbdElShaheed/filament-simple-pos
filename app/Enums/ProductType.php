<?php

namespace App\Enums;

enum ProductType: string
{
    case DELIVERABLE = 'deliverable';
    case DOWNLOADABLE = 'downloadable';

    public function label(): string
    {
        return match ($this) {
            self::DELIVERABLE => 'Deliverable',
            self::DOWNLOADABLE => 'Downloadable',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DELIVERABLE => 'primary',
            self::DOWNLOADABLE => 'secondary',
        };
    }
}
