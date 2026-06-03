<?php

require_once __DIR__ . '/../models/Paiement.php';
require_once __DIR__ . '/../models/Commande.php';

class PaiementController
{
    private $db;
    private $paiement;
    private $commande;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->paiement = new Paiement($db);
        $this->commande = new Commande($db);
    }

    public function index()
    {
        $currentPage = 'paiements';
        $title = 'Paiements';
        $paiements = $this->paiement->getAll();

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/paiements/index.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function create()
    {
        $currentPage = 'paiements';
        $title = 'Enregistrer un paiement';
        $commandes = $this->commande->getAll(false);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commandeId = (int) ($_POST['commande_id'] ?? 0);
            $montantPaye = $this->parseMontant($_POST['montant_paye'] ?? '');
            $datePaiement = $this->formatDateForSqlServer($_POST['date_paiement'] ?? '');

            if ($commandeId <= 0) {
                setFlash('danger', 'Veuillez selectionner une commande.');
            } elseif ($montantPaye === null || $montantPaye <= 0) {
                setFlash('danger', 'Le montant paye doit etre superieur a 0.');
            } elseif ($datePaiement === null) {
                setFlash('danger', 'La date de paiement est invalide.');
            } else {
                $commande = $this->commande->getById($commandeId);

                if (!$commande) {
                    setFlash('danger', 'Veuillez selectionner une commande.');
                    require __DIR__ . '/../views/header.php';
                    require __DIR__ . '/../views/paiements/create.php';
                    require __DIR__ . '/../views/footer.php';
                    return;
                }

                $reste = max(0, (float) $commande['total_commande'] - (float) $commande['total_paye']);

                if ($reste <= 0) {
                    setFlash('warning', 'Cette commande est deja payee.');
                } elseif ($montantPaye > $reste) {
                    setFlash('warning', 'Le montant paye depasse le reste a payer.');
                } else {
                    try {
                        $this->db->beginTransaction();
                        $this->paiement->create($commandeId, $montantPaye, $datePaiement);
                        $this->commande->updateStatut($commandeId);
                        $this->db->commit();
                        setFlash('success', 'Paiement enregistre avec succes.');
                        redirectTo('index.php?page=paiements');
                    } catch (Throwable $exception) {
                        if ($this->db->inTransaction()) {
                            $this->db->rollBack();
                        }
                        logAppError('Erreur creation paiement: ' . $exception->getMessage() . ' | POST=' . json_encode($_POST));
                        setFlash('danger', 'Impossible d enregistrer ce paiement. Verifiez les donnees saisies.');
                    }
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/paiements/create.php';
        require __DIR__ . '/../views/footer.php';
    }

    private function parseMontant($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $value = str_replace(["\xc2\xa0", ' ', 'FCFA', 'fcfa'], '', $value);
        $value = str_replace(',', '.', $value);

        if (!is_numeric($value)) {
            return null;
        }

        return round((float) $value, 2);
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
