<?php

class View
{
    public static string $layout_template = './views/layout.php';

    public static function render($page_template, $page_props = null)
    {
        if (is_array($page_props)) extract($page_props);
        require_once self::$layout_template;
    }
}
