<?php 
function async_base64_fonts()
{
    $fontcachefile = get_stylesheet_directory() . '/static/fontcache';
    $handle = fopen($fontcachefile, "r");
    $fontcache = fread($handle, filesize($fontcachefile));
    fclose($handle);

    $fontfile = '"' . str_replace(get_site_url(), '', get_stylesheet_directory_uri()) . '/typography/base64-fonts/woff.css' . $fontcache.'"'; ?>
        
    <script type="text/javascript">!function(){"use strict";var e=<?php echo $fontfile; ?>;function t(){if(window.localStorage&&window.XMLHttpRequest)if(a=e,window.localStorage&&localStorage.font_css_cache&&localStorage.font_css_cache_file===a)c(localStorage.font_css_cache);else{var t=new XMLHttpRequest;t.open("GET",e,!0),t.onreadystatechange=function(){4===t.readyState&&(c(t.responseText),localStorage.font_css_cache=t.responseText,localStorage.font_css_cache_file=e)},t.send()}else{var o=document.createElement("link");o.href=e,o.rel="stylesheet",o.type="text/css",document.getElementsByTagName("head")[0].appendChild(o),document.cookie="font_css_cache"}var a}function c(e){var t=document.createElement("style");t.setAttribute("type","text/css"),t.styleSheet?t.styleSheet.cssText=e:t.innerHTML=e,document.getElementsByTagName("head")[0].appendChild(t)}window.localStorage&&localStorage.font_css_cache||document.cookie.indexOf("font_css_cache")>-1?t():setTimeout(function(){t()},1)}();</script>
<noscript>
  <link rel="stylesheet" href="<?php echo $fontfile; ?>">
</noscript>
<?php
}
add_action('wp_head', 'async_base64_fonts');
?>