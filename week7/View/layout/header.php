<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'CampingKu') ?> - CampingKu</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <a class="brand" href="<?= e(url('equipment/index')) ?>">🏕️ CampingKu</a>
    <nav>
        <a href="<?= e(url('equipment/index')) ?>">Katalog</a>
        <a href="<?= e(url('order/history')) ?>">Riwayat Pesanan</a>
        <a href="<?= e(url('admin/dashboard')) ?>">Admin</a>
    </nav>
</header>
<main class="container">
