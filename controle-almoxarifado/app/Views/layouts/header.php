<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica se está logado para exibir o menu
if (!isset($_SESSION['user_id'])) {
    redirect('auth/login');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Almoxarifado</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
        }
        .sidebar a {
            color: #adb5bd;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background-color: #495057;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
            <i class="bi bi-box-seam"></i> Almoxarifado
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?= $_SESSION['user_name'] ?? 'Usuário' ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse bg-dark pt-3">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'] ?? '', 'dashboard') !== false ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'] ?? '', 'products') !== false ? 'active' : '' ?>" href="<?= base_url('products') ?>">
                            <i class="bi bi-box me-2"></i> Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'] ?? '', 'categories') !== false ? 'active' : '' ?>" href="<?= base_url('categories') ?>">
                            <i class="bi bi-tags me-2"></i> Categorias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_GET['url'] ?? '', 'movements') !== false ? 'active' : '' ?>" href="<?= base_url('movements') ?>">
                            <i class="bi bi-arrow-left-right me-2"></i> Movimentações
                        </a>
                    </li>
                    <?php if (($_SESSION['user_level'] ?? '') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-people me-2"></i> Usuários
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content bg-light">
