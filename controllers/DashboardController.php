<?php

require_once __DIR__ . '/../models/Statistique.php';

class DashboardController
{
    private $statistique;

    public function __construct(PDO $db)
    {
        $this->statistique = new Statistique($db);
    }

    public function index()
    {
        $currentPage = 'dashboard';
        $title = 'Tableau de bord';
        $stats = $this->statistique->getDashboardStats();
        $platsPlusCommandes = $this->statistique->getPlatsPlusCommandes();
        $recettesParJour = $this->statistique->getRecettesParJour();
        $commandesParStatut = $this->statistique->getCommandesParStatut();

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/dashboard.php';
        require __DIR__ . '/../views/footer.php';
    }
}
