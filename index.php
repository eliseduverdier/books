<?php

use App\BookApp;
use App\User\Authentication;

require dirname(__DIR__) . '/books/app/vendor/autoload.php';
require dirname(__DIR__) . '/books/app/src/BookApp.php';
require dirname(__DIR__) . '/books/app/src/Debug/util.php';

// ROUTER

$action = $_GET['action'] ?? null;
$slug = $_GET['slug'] ?? null;
$filter = $_GET['filter'] ?? [];
$sort = $_GET['sort'] ?? [];
// Working only on localhost, sadly
//if (preg_match('~^/([a-z_]+)/([a-z]+)$~', $_SERVER['REQUEST_URI'], $m)) {
//    $slug = $m[1];
//    $action = $m[2];
//} elseif (preg_match('~^/([a-z_]+)$~', $_SERVER['REQUEST_URI'], $m)) {
//    $slug = $m[1];
//}

// AUTH
$authenticated = (new Authentication())->isAuthenticated();

//// DISPATCH
if ($authenticated) {
    switch ($action) {
        case 'edit':
        case 'show':
            $_POST
                ? (new BookApp())->edit($_POST, $slug)
                : (new BookApp())->show($slug, true);
            break;
        case 'delete':
            (new BookApp())->delete($slug);
            break;
        case 'logout':
            (new BookApp())->logout();
            break;
        default:
            if ($_POST) (new BookApp())->saveNew($_POST);
            (new BookApp())->list(true, $filter, $sort);
            break;
    }
}
if (!$authenticated) {
    switch ($action) {
        case 'login':
            $_POST
                ? (new BookApp())->login($_POST['username'], $_POST['password']) && (new BookApp())->list(true)
                : (new BookApp())->loginForm();
            break;
        case 'show':
            (new BookApp())->show($slug);
            break;
        default:
            (new BookApp())->list(false, $filter, $sort);
            break;
    }
}
