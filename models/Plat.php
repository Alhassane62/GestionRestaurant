<?php

class Plat
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $sql = "SELECT p.*, c.nom AS categorie_nom
                FROM plats p
                INNER JOIN categories c ON c.id = p.categorie_id
                ORDER BY p.nom";
        return $this->db->query($sql)->fetchAll();
    }

    public function search($keyword)
    {
        if (trim($keyword) === '') {
            return $this->getAll();
        }

        $sql = "SELECT p.*, c.nom AS categorie_nom
                FROM plats p
                INNER JOIN categories c ON c.id = p.categorie_id
                WHERE p.nom LIKE ? OR c.nom LIKE ?
                ORDER BY p.nom";
        $stmt = $this->db->prepare($sql);
        $term = '%' . $keyword . '%';
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM plats WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nom, $description, $prix, $categorieId)
    {
        $stmt = $this->db->prepare('INSERT INTO plats (nom, description, prix, categorie_id) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$nom, $description, $prix, $categorieId]);
    }

    public function update($id, $nom, $description, $prix, $categorieId)
    {
        $stmt = $this->db->prepare('UPDATE plats SET nom = ?, description = ?, prix = ?, categorie_id = ? WHERE id = ?');
        return $stmt->execute([$nom, $description, $prix, $categorieId, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM plats WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function hasCommandes($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM commande_plats WHERE plat_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
