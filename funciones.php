<?php
function sanitizar($str) {
    return htmlspecialchars(stripslashes(trim($str)));
}

function html_purify($dirty_html) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($dirty_html);
    return $clean_html;
}