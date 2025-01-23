<?php

namespace plugins\MenuBuilder\Providers;

use Illuminate\Support\ServiceProvider;

class MenuBuilderServiceProvider extends ServiceProvider
{
    public function boot(){

    }

    public function register(){
        $this->loadViewsFrom(__DIR__.'../../views','menubuilder');
    }
}
