<?php
session_start();

require_once __DIR__ . '/../classes/Equipment.php';
require_once __DIR__ . '/../classes/EquipmentRepository.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/OrderManager.php';

$repo   = new EquipmentRepository();
$orders = new OrderManager();

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
