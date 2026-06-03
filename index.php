<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/config/database.php';

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

try {
    $database = new Database();
    $pdo = $database->getConnection();

    switch ($page) {
        case 'categories':
            require_once __DIR__ . '/controllers/CategorieController.php';
            $controller = new CategorieController($pdo);
            break;
        case 'plats':
            require_once __DIR__ . '/controllers/PlatController.php';
            $controller = new PlatController($pdo);
            break;
        case 'clients':
            require_once __DIR__ . '/controllers/ClientController.php';
            $controller = new ClientController($pdo);
            break;
        case 'commandes':
            require_once __DIR__ . '/controllers/CommandeController.php';
            $controller = new CommandeController($pdo);
            break;
        case 'paiements':
            require_once __DIR__ . '/controllers/PaiementController.php';
            $controller = new PaiementController($pdo);
            break;
        case 'statistiques':
            require_once __DIR__ . '/controllers/StatistiqueController.php';
            $controller = new StatistiqueController($pdo);
            break;
        case 'dashboard':
        default:
            require_once __DIR__ . '/controllers/DashboardController.php';
            $controller = new DashboardController($pdo);
            $page = 'dashboard';
            break;
    }

    if (!method_exists($controller, $action)) {
        $action = 'index';
    }

    if ($id !== null) {
        $controller->$action($id);
    } else {
        $controller->$action();
    }
} catch (Throwable $exception) {
    $title = 'Erreur';
    $flash = [
        'type' => 'danger',
        'message' => $exception->getMessage(),
    ];
    require __DIR__ . '/views/header.php';
    ?>
    <div class="content-card">
        <div class="card-body-custom">
            <p class="mb-0">Impossible de charger la page demandee.</p>
        </div>
    </div>
    <?php
    require __DIR__ . '/views/footer.php';
}
