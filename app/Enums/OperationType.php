<?php

namespace App\Enums;

enum OperationType: int
{
    case makeRequest = 1; // done
    case makeOffer = 2; // done
    case updateRequest = 3; // done
    case updateOffer = 4;   // done
    case deleteRequest = 5;
    case deleteOffer = 6;   // done
    case addAds = 7;

    public static function values(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }

    public static function getIndex(string $value): int
    {
        $values = array_map(fn($enum) => $enum->value, self::cases());
        return array_search($value, $values, true);
    }
}
