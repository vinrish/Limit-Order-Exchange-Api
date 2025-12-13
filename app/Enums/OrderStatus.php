<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: int
{
    case OPEN = 1;
    case FILLED = 2;
    case CANCELED = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::FILLED => 'Filled',
            self::CANCELED => 'Canceled',
        };
    }
}
