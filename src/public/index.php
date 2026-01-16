<?php

define('APP_ROOT', dirname(__DIR__));

$uri = $_SERVER['DOCUMENT_URI'] ?? $_SERVER['REQUEST_URI'];

// Serve direct CSS requests for themes
if (preg_match('#^/designs/[^/]+/adminer(-dark)?\.css$#', $uri)) {
    $file = APP_ROOT . $uri;
    if (is_readable($file)) {
        header('Content-Type: text/css');
        readfile($file);
        exit;
    }
}

// For all other requests to Adminer
include APP_ROOT . '/adminer.php';
