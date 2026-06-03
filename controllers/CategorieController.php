<?php

require_once __DIR__ . '/../models/Categorie.php';

class CategorieController
{
    private $categorie;

    public function __construct(PDO $db)
    {
        $this->categorie = new Categorie($db);
    }

    public function index()
    {
        $currentPage = 'categories';
        $title = 'Categories';
        $categories = $this->categorie->getAll();

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/categories/index.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function create()
    {
        $currentPage = 'categories';
        $title = 'Ajouter une categorie';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');

            if ($nom === '') {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->categorie->create($nom);
                    setFlash('success', 'Categorie ajoutee avec succes.');
                    redirectTo('index.php?page=categories');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible d enregistrer cette categorie.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/categories/create.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function edit($id)
    {
        $currentPage = 'categories';
        $title = 'Modifier une categorie';
        $categorie = $this->categorie->getById($id);

        if (!$categorie) {
            setFlash('danger', 'Categorie introuvable.');
            redirectTo('index.php?page=categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');

            if ($nom === '') {
                setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            } else {
                try {
                    $this->categorie->update($id, $nom);
                    setFlash('success', 'Categorie modifiee avec succes.');
                    redirectTo('index.php?page=categories');
                } catch (Throwable $exception) {
                    setFlash('danger', 'Impossible de modifier cette categorie.');
                }
            }
        }

        require __DIR__ . '/../views/header.php';
        require __DIR__ . '/../views/categories/edit.php';
        require __DIR__ . '/../views/footer.php';
    }

    public function delete($id)
    {
        if ($this->categorie->hasPlats($id)) {
            setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
        } else {
            try {
                $this->categorie->delete($id);
                setFlash('success', 'Categorie supprimee avec succes.');
            } catch (Throwable $exception) {
                setFlash('danger', 'Impossible de supprimer cet element car il est lie a des donnees.');
            }
        }

        redirectTo('index.php?page=categories');
    }

    public function quick_create()
    {
        $returnUrl = $_POST['return_url'] ?? 'index.php?page=categories';
        if (strpos($returnUrl, 'index.php') !== 0) {
            $returnUrl = 'index.php?page=categories';
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirectTo($returnUrl);
        }

        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            setFlash('danger', 'Veuillez remplir tous les champs obligatoires.');
            redirectTo($returnUrl);
        }

        try {
            $this->categorie->create($nom);
            setFlash('success', 'Categorie ajoutee avec succes.');
        } catch (Throwable $exception) {
            setFlash('danger', 'Impossible d enregistrer cette categorie.');
        }

        redirectTo($returnUrl);
    }
}
