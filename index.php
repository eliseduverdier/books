<?php

use App\BookApp;
use App\User\Authentication;

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/BookApp.php';

// ROUTER

$action = $_GET['action'] ?? null;
$slug = $_GET['slug'] ?? null;
// Working only on localhost, sadly
//if (preg_match('~^/([a-z_]+)/([a-z]+)$~', $_SERVER['REQUEST_URI'], $m)) {
//    $slug = $m[1];
//    $action = $m[2];
//} elseif (preg_match('~^/([a-z_]+)$~', $_SERVER['REQUEST_URI'], $m)) {
//    $slug = $m[1];
//}

// AUTH

$authenticated = (new Authentication())->isAuthenticated();

// DISPATCHER
if ($authenticated) {
    if (!$action && !$slug && $_POST) {
        (new BookApp())->saveNew($_POST);
        (new BookApp())->list(true);
    } elseif (!$action && $slug) {
        if ($_POST) {
            (new BookApp())->edit($_POST, $slug);
        } else {
            (new BookApp())->show($slug);
        }
    } elseif ($action === 'delete' && $slug) {
        (new BookApp())->delete($slug);
    } else {
        (new BookApp())->list(true);
    }
} else {
    if ($action === 'login') {
        if ($_POST) {
            (new BookApp())->login($_POST['username'], $_POST['password']);
        (new BookApp())->list(true);
        } else {
            (new BookApp())->loginForm();
        }
    } else {
        (new BookApp())->list();
    }
}
