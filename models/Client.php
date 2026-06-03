<?php

class Client
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        return $this->db->query('SELECT * FROM clients ORDER BY nom')->fetchAll();
    }

    public function search($keyword)
    {
        if (trim($keyword) === '') {
            return $this->getAll();
        }

        $stmt = $this->db->prepare('SELECT * FROM clients WHERE nom LIKE ? OR telephone LIKE ? ORDER BY nom');
        $term = '%' . $keyword . '%';
        $stmt->execute([$term, $term]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM clients WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nom, $telephone)
    {
        $stmt = $this->db->prepare('INSERT INTO clients (nom, telephone) VALUES (?, ?)');
        return $stmt->execute([$nom, $telephone]);
    }

    public function update($id, $nom, $telephone)
    {
        $stmt = $this->db->prepare('UPDATE clients SET nom = ?, telephone = ? WHERE id = ?');
        return $stmt->execute([$nom, $telephone, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM clients WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function hasCommandes($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM commandes WHERE client_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
