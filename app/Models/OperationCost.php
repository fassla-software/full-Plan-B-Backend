<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_type',
        'category_slug',
        'cost'
    ];

    public function scopeGetCostByTypeAndCategory($query, $operationType, $categorySlug)
    {
        return $query->where('operation_type', $operationType)
            ->where('category_slug', $categorySlug)
            ->value('cost');
    }
}
