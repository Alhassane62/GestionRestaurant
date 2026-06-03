<?php

require_once __DIR__ . '/../models/Statistique.php';

class StatistiqueController
{
    private $statistique;

    public function __construct(PDO $db)
    {
        $this->statistique = new Statistique($db);
    }

    public function index()
    {
        $currentPage = 'statistiques';
        $title = 'Statistiques';
        $platsPlusCommandes = $this->statistique->getPlatsPlusCommandes();
        $recettes = $this->statistique->getRecettes();
        $commandesDuJour = $this->statistique->getCommandesDuJour();

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/statistiques.php';
        require __DIR__ . '/../views/footer.php';
    }
}
