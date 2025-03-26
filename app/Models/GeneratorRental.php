<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratorRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'year_of_manufacture',
        'lat',
        'long',
        'special_rental_conditions',
        'current_generator_location',
        'generator_image',
        'generator_power',
        'model',
        'maximum_number_of_continuous_operating_hours',
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
}
