<?php

class Categorie
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        return $this->db->query('SELECT * FROM categories ORDER BY nom')->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nom)
    {
        $stmt = $this->db->prepare('INSERT INTO categories (nom) VALUES (?)');
        return $stmt->execute([$nom]);
    }

    public function update($id, $nom)
    {
        $stmt = $this->db->prepare('UPDATE categories SET nom = ? WHERE id = ?');
        return $stmt->execute([$nom, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function hasPlats($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM plats WHERE categorie_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
