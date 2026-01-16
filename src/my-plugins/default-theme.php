<?php

/** Adminer Plugin - Load a default light or dark theme */
class DefaultTheme extends Adminer\Plugin
{
    // Load light and/or dark theme CSS based on env variable
    public function css()
    {
        $theme = getenv('ADMINER_THEME');
        if (!$theme) {
            return [];
        }

        $css = [];
        $lightCss = "designs/{$theme}/adminer.css";
        $darkCss = "designs/{$theme}/adminer-dark.css";

        // use APP_ROOT to check files @see /public/index.php
        $basePath = defined('APP_ROOT') ? APP_ROOT . '/' : '';

        if (file_exists($basePath . $lightCss)) {
            $css[$lightCss] = 'light';
        }

        if (file_exists($basePath . $darkCss)) {
            $css[$darkCss] = 'dark';
        }

        return $css;
    }
}
