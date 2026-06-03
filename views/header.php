<?php
$flash = $flash ?? getFlash();
$currentPage = $currentPage ?? 'dashboard';
$title = $title ?? 'Gestion Restaurant';
$isToday = isset($_GET['today']) && $_GET['today'] == '1';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> - Gestion Restaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --topbar-height: 60px;
            --primary: #1a3a6b;
            --primary-light: #2450a0;
            --accent: #e8af30;
            --bg-sidebar: #12285a;
            --bg-page: #f0f2f7;
        }
        body { background-color: var(--bg-page); font-family: "Segoe UI", sans-serif; margin: 0; }
        .topbar { height: var(--topbar-height); background: var(--primary); color: white; display: flex; align-items: center; padding: 0 1.5rem; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-shadow: 0 2px 8px rgba(0,0,0,.2); }
        .topbar .brand { font-weight: 700; font-size: 1.1rem; letter-spacing: .5px; color: white; text-decoration: none; }
        .topbar .brand span { color: var(--accent); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .user-name { font-size: .9rem; opacity: .85; }
        .avatar { width: 36px; height: 36px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; color: var(--primary); }
        .sidebar { width: var(--sidebar-width); background: var(--bg-sidebar); position: fixed; top: var(--topbar-height); left: 0; bottom: 0; overflow-y: auto; padding: 1.5rem 0; z-index: 999; }
        .sidebar-section-title { font-size: .7rem; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,.35); padding: .75rem 1.25rem .25rem; margin-top: .5rem; }
        .sidebar a { display: flex; align-items: center; gap: .65rem; color: rgba(255,255,255,.75); text-decoration: none; font-size: .9rem; padding: .6rem 1.25rem; border-left: 3px solid transparent; transition: all .15s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,.1); color: white; }
        .sidebar a.active { border-left-color: var(--accent); font-weight: 600; }
        .sidebar a i { font-size: 1.05rem; }
        .main-content { margin-left: var(--sidebar-width); margin-top: var(--topbar-height); padding: 2rem; min-height: calc(100vh - var(--topbar-height)); }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; color: var(--primary); margin: 0; }
        .breadcrumb { font-size: .8rem; margin: 0; }
        .stat-card { background: white; border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); border-bottom: 3px solid transparent; min-height: 94px; }
        .stat-card.blue { border-bottom-color: #2450a0; }
        .stat-card.gold { border-bottom-color: var(--accent); }
        .stat-card.green { border-bottom-color: #1a7a4a; }
        .stat-card.red { border-bottom-color: #c0392b; }
        .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex: 0 0 48px; }
        .stat-icon.blue { background: #e8f0fb; color: #2450a0; }
        .stat-icon.gold { background: #fdf3d8; color: #b8860b; }
        .stat-icon.green { background: #e0f5e9; color: #1a7a4a; }
        .stat-icon.red { background: #fde8e6; color: #c0392b; }
        .stat-label { font-size: .78rem; color: #888; margin-bottom: 2px; }
        .stat-value { font-size: 1.45rem; font-weight: 700; color: var(--primary); line-height: 1.15; overflow-wrap: anywhere; }
        .content-card { background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden; }
        .content-card .card-head { padding: 1rem 1.25rem; border-bottom: 1px solid #eef0f5; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .content-card .card-head h5 { margin: 0; font-size: .95rem; font-weight: 700; color: var(--primary); }
        .card-body-custom { padding: 1.25rem; }
        .custom-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .custom-table thead th { background: var(--primary); color: white; padding: .75rem 1rem; font-weight: 600; font-size: .78rem; text-transform: uppercase; letter-spacing: .5px; border: none; white-space: nowrap; }
        .custom-table tbody tr { border-bottom: 1px solid #f0f2f7; transition: background .1s; }
        .custom-table tbody tr:hover { background: #f8f9ff; }
        .custom-table tbody td { padding: .75rem 1rem; color: #444; vertical-align: middle; }
        .badge-filiere { background: #e8f0fb; color: #2450a0; font-size: .72rem; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
        .form-section-title { font-size: .75rem; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 1rem; padding-bottom: .5rem; border-bottom: 1px solid #eef0f5; }
        .form-label { font-size: .82rem; font-weight: 600; color: #555; margin-bottom: 4px; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #dde0ea; font-size: .875rem; padding: .5rem .85rem; color: #333; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(36,80,160,.12); }
        .btn-primary-custom, .btn-secondary-custom { border-radius: 8px; padding: .5rem 1.25rem; font-size: .875rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: .4rem; text-decoration: none; transition: background .15s; border: none; }
        .btn-primary-custom { background: var(--primary); color: white; }
        .btn-primary-custom:hover { background: var(--primary-light); color: white; }
        .btn-secondary-custom { background: white; color: #555; border: 1px solid #dde0ea; }
        .btn-secondary-custom:hover { background: #f5f6fa; color: #333; }
        .btn-icon { width: 32px; height: 32px; border-radius: 7px; display: inline-flex; align-items: center; justify-content: center; font-size: .95rem; cursor: pointer; border: none; text-decoration: none; transition: background .12s; }
        .btn-icon.edit { background: #fff4e0; color: #b8860b; }
        .btn-icon.show { background: #e8f0fb; color: #2450a0; }
        .btn-icon.delete { background: #fde8e6; color: #c0392b; }
        .alert-custom { border-radius: 10px; padding: .85rem 1.1rem; display: flex; align-items: center; gap: .75rem; font-size: .875rem; font-weight: 500; border: none; margin-bottom: 1.25rem; }
        .alert-success-custom { background: #e0f5e9; color: #1a7a4a; }
        .alert-danger-custom { background: #fde8e6; color: #c0392b; }
        .alert-warning-custom { background: #fdf3d8; color: #8a6400; }
        .empty-state { text-align: center; padding: 3rem 1rem; color: #999; }
        .empty-state i { font-size: 3rem; margin-bottom: .75rem; display: block; }
        @media (max-width: 900px) {
            .topbar { position: static; }
            .sidebar { position: static; width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); padding: .75rem 0; }
            .sidebar-section-title { display: none; }
            .main-content { margin: 0; padding: 1rem; }
            .page-header { align-items: flex-start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a href="index.php?page=dashboard" class="brand">Gestion <span>Restaurant</span></a>
        <div class="topbar-right">
            <span class="user-name">Admin</span>
            <div class="avatar">AD</div>
        </div>
    </div>

    <div class="sidebar">
        <div class="sidebar-section-title">Navigation</div>
        <a href="index.php?page=dashboard" class="<?= activeClass($currentPage, 'dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a>
        <a href="index.php?page=categories" class="<?= activeClass($currentPage, 'categories') ?>"><i class="bi bi-tags"></i> Categories</a>
        <a href="index.php?page=plats" class="<?= activeClass($currentPage, 'plats') ?>"><i class="bi bi-egg-fried"></i> Plats</a>
        <a href="index.php?page=clients" class="<?= activeClass($currentPage, 'clients') ?>"><i class="bi bi-people"></i> Clients</a>
        <a href="index.php?page=commandes" class="<?= activeClass($currentPage, 'commandes', !$isToday) ?>"><i class="bi bi-receipt"></i> Commandes</a>
        <a href="index.php?page=paiements" class="<?= activeClass($currentPage, 'paiements') ?>"><i class="bi bi-cash-coin"></i> Paiements</a>
        <a href="index.php?page=commandes&today=1" class="<?= activeClass($currentPage, 'commandes', $isToday) ?>"><i class="bi bi-calendar-day"></i> Commandes du jour</a>
        <div class="sidebar-section-title">Analyse</div>
        <a href="index.php?page=statistiques" class="<?= activeClass($currentPage, 'statistiques') ?>"><i class="bi bi-bar-chart"></i> Statistiques</a>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div>
                <h1><?= e($title) ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
                        <li class="breadcrumb-item active"><?= e($title) ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if ($flash): ?>
            <?php $type = $flash['type'] === 'warning' ? 'warning' : ($flash['type'] === 'danger' ? 'danger' : 'success'); ?>
            <div class="alert-custom alert-<?= e($type) ?>-custom">
                <i class="bi bi-<?= $type === 'success' ? 'check-circle-fill' : ($type === 'warning' ? 'exclamation-triangle-fill' : 'x-circle-fill') ?>"></i>
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>
