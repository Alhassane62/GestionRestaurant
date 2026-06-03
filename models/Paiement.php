<?php

class Paiement
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $sql = "SELECT p.*, cl.nom AS client_nom, co.date_commande
                FROM paiements p
                INNER JOIN commandes co ON co.id = p.commande_id
                INNER JOIN clients cl ON cl.id = co.client_id
                ORDER BY p.date_paiement DESC, p.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($commandeId, $montantPaye, $datePaiement)
    {
        $stmt = $this->db->prepare('INSERT INTO paiements (commande_id, montant_paye, date_paiement) VALUES (?, ?, ?)');
        return $stmt->execute([$commandeId, number_format((float) $montantPaye, 2, '.', ''), $datePaiement]);
    }

    public function getTotalPayeByCommande($commandeId)
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(montant_paye), 0) FROM paiements WHERE commande_id = ?');
        $stmt->execute([$commandeId]);
        return (float) $stmt->fetchColumn();
    }
}
