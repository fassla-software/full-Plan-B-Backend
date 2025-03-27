<?php

namespace App\Models;

use App\Models\User;
use Modules\Service\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScaffoldingAndMetalFormworkRental extends Model
{
    use HasFactory;

    protected $table = 'scaffolding_rentals';

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'lat',
        'long',
        'special_rental_conditions',
        'current_equipment_location',
        'equipment_images',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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
