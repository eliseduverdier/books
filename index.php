<?php

use App\BookApp;
use App\User\Authentication;

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/BookApp.php';

// ROUTER

$action = null;
$slug = null;
if (preg_match('~^/([a-z_]+)/([a-z]+)$~', $_SERVER['REQUEST_URI'], $m)) {
    $slug = $m[1];
    $action = $m[2];
} elseif (preg_match('~^/([a-z_]+)$~', $_SERVER['REQUEST_URI'], $m)) {
    $slug = $m[1];
}

// DISPATCHER

if (!$action && !$slug && $_POST) {// create
    (new BookApp())->saveNew($_POST);
} elseif (!$action && $slug) {
    if ($_POST) { // edit
        (new BookApp())->edit($_POST, $slug);
    } else {// show
        (new BookApp())->show($slug);
    }
} elseif ($action === 'delete' && $slug) {// delete
    (new BookApp())->delete($slug);
} else {
    (new BookApp())->list();
}
