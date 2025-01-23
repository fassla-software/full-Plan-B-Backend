<?php

namespace plugins\FormBuilder\Providers;

use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    public function boot(){

    }

    public function register(){
        $this->loadViewsFrom(__DIR__.'../../views','formbuilder');
    }
}
