<?php

require_once __DIR__ . '/../models/Plat.php';
require_once __DIR__ . '/../models/Categorie.php';

class PlatController
{
    private $plat;
    private $categorie;

    public function __construct(PDO $db)
    {
        $this->plat = new Plat($db);
        $this->categorie = new Categorie($db);
    }

    public function index()
    {
        $currentPage = 'plats';
        $title = 'Plats';
        $keyword = trim($_GET['q'] ?? '');
        $plats = $this->plat->search($keyword);

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/plats/index.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function create()
    {
        $currentPage = 'plats';
        $title = 'Ajouter un plat';
        $categories = $this->categorie->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $prix = $_POST['prix'] ?? '';
            $categorieId = (int) ($_POST['categorie_id'] ?? 0);

            if ($nom === '' || $prix === '' || $categorieId <= 0 || (float) $prix < 0) {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->plat->create($nom, $description, (float) $prix, $categorieId);
                    setFlash('success', 'Plat ajoute avec succes.');
                    redirectTo('index.php?page=plats');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible d enregistrer ce plat.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/plats/create.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function edit($id)
    {
        $currentPage = 'plats';
        $title = 'Modifier un plat';
        $plat = $this->plat->getById($id);
        $categories = $this->categorie->getAll();

        if (!$plat) {
            setFlash('danger', 'Plat introuvable.');
            redirectTo('index.php?page=plats');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $prix = $_POST['prix'] ?? '';
            $categorieId = (int) ($_POST['categorie_id'] ?? 0);

            if ($nom === '' || $prix === '' || $categorieId <= 0 || (float) $prix < 0) {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->plat->update($id, $nom, $description, (float) $prix, $categorieId);
                    setFlash('success', 'Plat modifie avec succes.');
                    redirectTo('index.php?page=plats');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible de modifier ce plat.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/plats/edit.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function delete($id)
    {
        if ($this->plat->hasCommandes($id)) {
            setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
        } else {
            try {
                $this->plat->delete($id);
                setFlash('success', 'Plat supprime avec succes.');
            } catch (Throwable $exception) {
                setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
            }
        }

        redirectTo('index.php?page=plats');
    }
}
