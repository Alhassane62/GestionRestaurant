<?php

require_once __DIR__ . '/../models/Commande.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Plat.php';

class CommandeController
{
    private $db;
    private $commande;
    private $client;
    private $plat;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->commande = new Commande($db);
        $this->client = new Client($db);
        $this->plat = new Plat($db);
    }

    public function index()
    {
        $currentPage = 'commandes';
        $todayOnly = isset($_GET['today']) && $_GET['today'] == '1';
        $title = $todayOnly ? 'Commandes du jour' : 'Commandes';
        $commandes = $this->commande->getAll($todayOnly);

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/commandes/index.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function create()
    {
        $currentPage = 'commandes';
        $title = 'Nouvelle commande';
        $clients = $this->client->getAll();
        $plats = $this->plat->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = (int) ($_POST['client_id'] ?? 0);
            $dateCommande = $this->formatDateForSqlServer($_POST['date_commande'] ?? '');
            $selectedPlats = isset($_POST['plats']) && is_array($_POST['plats']) ? $_POST['plats'] : [];
            $quantites = isset($_POST['quantites']) && is_array($_POST['quantites']) ? $_POST['quantites'] : [];
            $lignes = [];
            $hasSelectedPlat = !empty($selectedPlats);
            $invalidQuantity = false;

            foreach (array_unique($selectedPlats) as $platId) {
                $platId = (int) $platId;
                $quantite = (int) ($quantites[$platId] ?? 0);

                if ($platId <= 0) {
                    continue;
                }

                if ($quantite <= 0) {
                    $invalidQuantity = true;
                    break;
                }

                $lignes[] = [
                    'plat_id' => $platId,
                    'quantite' => $quantite,
                ];
            }

            if ($clientId <= 0) {
                setFlash('danger', 'Veuillez selectionner un client.');
            } elseif ($dateCommande === null) {
                setFlash('danger', 'La date de commande est invalide.');
            } elseif (!$hasSelectedPlat) {
                setFlash('danger', 'Veuillez selectionner au moins un plat.');
            } elseif ($invalidQuantity || empty($lignes)) {
                setFlash('danger', 'La quantite doit etre superieure a 0.');
            } else {
                try {
                    $this->db->beginTransaction();
                    $commandeId = $this->commande->create($clientId, $dateCommande);

                    if ($commandeId <= 0) {
                        throw new RuntimeException('Identifiant de commande non recupere.');
                    }

                    foreach ($lignes as $ligne) {
                        $plat = $this->plat->getById($ligne['plat_id']);
                        if (!$plat) {
                            throw new RuntimeException('Plat introuvable.');
                        }

                        if ((float) $plat['prix'] < 0) {
                            throw new RuntimeException('Prix du plat invalide.');
                        }

                        $this->commande->addPlat($commandeId, $ligne['plat_id'], $ligne['quantite'], $plat['prix']);
                    }

                    $this->commande->updateStatut($commandeId);
                    $this->db->commit();
                    setFlash('success', 'Commande enregistree avec succes.');
                    redirectTo('index.php?page=commandes&action=show&id=' . $commandeId);
                } catch (Throwable $exception) {
                    if ($this->db->inTransaction()) {
                        $this->db->rollBack();
                    }
                    logAppError('Erreur creation commande: ' . $exception->getMessage() . ' | POST=' . json_encode($_POST));
                    setFlash('danger', 'Impossible d enregistrer cette commande. Verifiez les donnees saisies.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/commandes/create.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function show($id)
    {
        $currentPage = 'commandes';
        $title = 'Details de la commande';
        $commande = $this->commande->getById($id);

        if (!$commande) {
            setFlash('danger', 'Commande introuvable.');
            redirectTo('index.php?page=commandes');
        }

        $details = $this->commande->getDetails($id);
        $resteAPayer = max(0, (float) $commande['total_commande'] - (float) $commande['total_paye']);

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/commandes/show.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function delete($id)
    {
        if ($this->commande->hasPaiements($id)) {
            setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
        } else {
            try {
                $this->commande->delete($id);
                setFlash('success', 'Commande supprimee avec succes.');
            } catch (Throwable $exception) {
                setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
            }
        }

        redirectTo('index.php?page=commandes');
    }

    private function formatDateForSqlServer($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i:s', 'Y-m-d H:i', 'd/m/Y H:i'];

        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            $errors = DateTime::getLastErrors();
            if ($date instanceof DateTime && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return $date->format('Y-m-d H:i:s');
            }
        }

        try {
            return (new DateTime($value))->format('Y-m-d H:i:s');
        } catch (Throwable $exception) {
            return null;
        }
    }
}
