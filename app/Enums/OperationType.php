<?php

namespace App\Enums;

enum OperationType: int
{
    case makeRequest = 1;
    case makeOffer = 2;
    case updateRequest = 3;
    case updateOffer = 4;
    case deleteRequest = 5;
    case deleteOffer = 6;
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
