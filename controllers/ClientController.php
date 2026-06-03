<?php

require_once __DIR__ . '/../models/Client.php';

class ClientController
{
    private $client;

    public function __construct(PDO $db)
    {
        $this->client = new Client($db);
    }

    public function index()
    {
        $currentPage = 'clients';
        $title = 'Clients';
        $keyword = trim($_GET['q'] ?? '');
        $clients = $this->client->search($keyword);

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/clients/index.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function create()
    {
        $currentPage = 'clients';
        $title = 'Ajouter un client';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');

            if ($nom === '' || $telephone === '') {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->client->create($nom, $telephone);
                    setFlash('success', 'Client ajoute avec succes.');
                    redirectTo('index.php?page=clients');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible d enregistrer ce client.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/clients/create.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function edit($id)
    {
        $currentPage = 'clients';
        $title = 'Modifier un client';
        $client = $this->client->getById($id);

        if (!$client) {
            setFlash('danger', 'Client introuvable.');
            redirectTo('index.php?page=clients');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');

            if ($nom === '' || $telephone === '') {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->client->update($id, $nom, $telephone);
                    setFlash('success', 'Client modifie avec succes.');
                    redirectTo('index.php?page=clients');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible de modifier ce client.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/clients/edit.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function delete($id)
    {
        if ($this->client->hasCommandes($id)) {
            setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
        } else {
            try {
                $this->client->delete($id);
                setFlash('success', 'Client supprime avec succes.');
            } catch (Throwable $exception) {
                setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
            }
        }

        redirectTo('index.php?page=clients');
    }

    public function quick_create()
    {
        $returnUrl = $_POST['return_url'] ?? 'index.php?page=clients';
        if (strpos($returnUrl, 'index.php') !== 0) {
            $returnUrl = 'index.php?page=clients';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirectTo($returnUrl);
        }

        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        if ($nom === '' || $telephone === '') {
            setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            redirectTo($returnUrl);
        }

        try {
            $this->client->create($nom, $telephone);
            setFlash('success', 'Client ajoute avec succes.');
        } catch (Throwable $exception) {
            setFlash('danger', 'Impossible d enregistrer ce client.');
        }

        redirectTo($returnUrl);
    }
}
