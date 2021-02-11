<?php

class View
{
  public static function render($page_template, $page_props = null)
  {
    if (is_array($page_props)) {
      extract($page_props);
    }
    require './views/layout.php';
  }
}
