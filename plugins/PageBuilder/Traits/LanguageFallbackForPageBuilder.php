<?php

namespace plugins\PageBuilder\Traits;

trait LanguageFallbackForPageBuilder
{
    public function setting_item($item){
        $settings = $this->get_settings();
        return $settings[$item] ?? null;
    }
}
