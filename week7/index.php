<?php
/**
 * Front Controller (satu pintu masuk aplikasi)
 * Alur: USER -> REQUEST (index.php?r=controller/action) -> CONTROLLER -> MODEL -> VIEW
 */
require_once __DIR__ . '/config/bootstrap.php';

// Daftar route yang diizinkan: nama => [class Controller, method yang boleh dipanggil]
$routes = [
    'equipment' => [EquipmentController::class, ['index', 'show']],
    'order'     => [OrderController::class,     ['process', 'history']],
    'admin'     => [AdminController::class,     ['login', 'dashboard', 'process', 'logout']],
];

$route = $_GET['r'] ?? 'equipment/index';
[$name, $action] = array_pad(explode('/', $route, 2), 2, 'index');

if (!isset($routes[$name]) || !in_array($action, $routes[$name][1], true)) {
    http_response_code(404);
    echo '<h1>404</h1><p>Halaman tidak ditemukan. <a href="index.php">Kembali ke katalog</a></p>';
    exit;
}

$controllerClass = $routes[$name][0];
$controller = new $controllerClass($repo, $orders);
$controller->$action();
