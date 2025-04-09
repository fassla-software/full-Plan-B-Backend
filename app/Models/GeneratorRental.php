<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneratorRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'special_rental_conditions',
        'rental_status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(generatorRentalLocation::class, 'generator_rental_id');
    }
}
