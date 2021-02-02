<?php
function lsi_logo() {
    $logo_style = '<style type="text/css">';
    $logo_style .= 'h1 a {background-image: url(' . plugin_dir_url(__DIR__) . '/img/lsi.png) !important;}';
    $logo_style .= '</style>';
    echo $logo_style;
}
add_action('login_head', 'lsi_logo');

function lsi_url() {
    return 'https://www.lsisoftware.pl/';
}
add_filter('login_headerurl', 'lsi_url');
