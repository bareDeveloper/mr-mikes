<?php

//
// Inline SVG Icons
//
function svg($iconClass)
{
  $baseUrl = get_stylesheet_directory() . '/static/icons/';
  $svgPath = $baseUrl . 'custom/' . $iconClass . '.svg';

  if (file_exists($svgPath)) {
    return file_get_contents($svgPath);
  } else {
    write_log("$svgPath not found!");
    return "$svgPath not found!";
  }
};

?>