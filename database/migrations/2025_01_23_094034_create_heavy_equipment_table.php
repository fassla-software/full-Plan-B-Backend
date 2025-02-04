<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('heavy_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('size')->nullable(); // حجم
            $table->string('model')->nullable(); // الطراز
            $table->year('year_of_manufacture')->nullable(); // الموديل (سنة الصنع)
            $table->string('moves_on')->nullable(); // يتحرك على
            $table->string('current_equipment_location')->nullable(); // موقع المعدة الحالي
            $table->string('data_certificate_image')->nullable(); // صورة شهادة البيانات
            $table->string('driver_license_front_image')->nullable(); // صورة رخصة سائق المعدة من الامام
            $table->string('driver_license_back_image')->nullable(); // صورة رخصة سائق المعدة من الخلف
            $table->string('tractor_license_front_image')->nullable(); // صورة رخصة سائق المعدة من الخلف
            $table->string('tractor_license_back_image')->nullable(); // صورة رخصة سائق المعدة من 
            $table->string('flatbed_license_front_image')->nullable(); // صورة رخصة سائق المعدة من 
            $table->string('flatbed_license_back_image')->nullable(); // صورة رخصة سائق المعدة من 
            
            
            $table->json('additional_equipment_images')->nullable(); // اضف صور المعدة من زوايا مختلفة
            $table->text('special_rental_conditions')->nullable(); // اضف الشروط الخاصة بتأجير هذه المعدة
            $table->string('blade_width')->nullable(); // عرض الشفرة(الباكيت)
            $table->string('blade_width_near_digging_arm')->nullable(); // عرض الشفرة(الباكيت) ناحية ذراع الحفر
            $table->integer('engine_power')->nullable(); // قدرة المحرك
            $table->string('scraper_width')->nullable(); // عرض سلاح الكشط
            $table->string('sprinkler_system_type')->nullable(); // نظام تشغيل الرشاشات
            $table->decimal('tank_capacity')->nullable(); // حدد سعة الخزان
            $table->string('panda_width')->nullable(); // ادخل عرض الباندا(حامل الرشاشات)
            $table->boolean('has_bitumen_temp_gauge')->default(true); // المنظومة مزودة ببيان لدرجة حرارة البيتومين داخل الخزان
            $table->boolean('has_bitumen_level_gauge')->default(true); // المنظومة مزودة ببيان لمستوى البيتومين داخل الخزان
            $table->integer('max_equipment_load')->nullable(); // اقصى حمولة للمعدة
            $table->string('boom_length')->nullable(); // ادخل طول البوم(الذراع)
            $table->integer('load_at_max_boom_height')->nullable(); // ادخل حمولة المعدة عند اقصى ارتفاع للبوم
            $table->integer('load_at_max_horizontal_boom_extension')->nullable(); // ادخل حمولة المعدة عند اقصى امتداد افقي للبوم
            $table->integer('max_lifting_point')->nullable(); // ادخل اقصى نقطة رفع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipment');
    }
};
