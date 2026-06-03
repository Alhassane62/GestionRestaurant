<?php

class Commande
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll($todayOnly = false)
    {
        $where = $todayOnly ? 'WHERE CAST(co.date_commande AS DATE) = CAST(GETDATE() AS DATE)' : '';
        $sql = "SELECT co.id, co.date_commande, co.statut, cl.nom AS client_nom,
                       COALESCE(SUM(cp.quantite * cp.prix_unitaire), 0) AS total_commande,
                       COALESCE((SELECT SUM(pa.montant_paye) FROM paiements pa WHERE pa.commande_id = co.id), 0) AS total_paye
                FROM commandes co
                INNER JOIN clients cl ON cl.id = co.client_id
                LEFT JOIN commande_plats cp ON cp.commande_id = co.id
                $where
                GROUP BY co.id, co.date_commande, co.statut, cl.nom
                ORDER BY co.date_commande DESC, co.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT co.*, cl.nom AS client_nom, cl.telephone,
                       COALESCE((SELECT SUM(cp.quantite * cp.prix_unitaire) FROM commande_plats cp WHERE cp.commande_id = co.id), 0) AS total_commande,
                       COALESCE((SELECT SUM(pa.montant_paye) FROM paiements pa WHERE pa.commande_id = co.id), 0) AS total_paye
                FROM commandes co
                INNER JOIN clients cl ON cl.id = co.client_id
                WHERE co.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getDetails($id)
    {
        $sql = "SELECT cp.*, p.nom AS plat_nom
                FROM commande_plats cp
                INNER JOIN plats p ON p.id = cp.plat_id
                WHERE cp.commande_id = ?
                ORDER BY p.nom";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public function create($clientId, $dateCommande)
    {
        $sql = "INSERT INTO commandes (client_id, date_commande, statut)
                OUTPUT INSERTED.id
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clientId, $dateCommande, 'En attente']);

        $id = $stmt->fetchColumn();
        $stmt->closeCursor();

        return (int) $id;
    }

    public function addPlat($commandeId, $platId, $quantite, $prixUnitaire)
    {
        $stmt = $this->db->prepare('INSERT INTO commande_plats (commande_id, plat_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$commandeId, $platId, $quantite, $prixUnitaire]);
    }

    public function delete($id)
    {
        $this->db->prepare('DELETE FROM commande_plats WHERE commande_id = ?')->execute([$id]);
        $stmt = $this->db->prepare('DELETE FROM commandes WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function hasPaiements($id)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM paiements WHERE commande_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function getTotal($id)
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(quantite * prix_unitaire), 0) FROM commande_plats WHERE commande_id = ?');
        $stmt->execute([$id]);
        return (float) $stmt->fetchColumn();
    }

    public function getTotalPaye($id)
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(montant_paye), 0) FROM paiements WHERE commande_id = ?');
        $stmt->execute([$id]);
        return (float) $stmt->fetchColumn();
    }

    public function getResteAPayer($id)
    {
        return max(0, $this->getTotal($id) - $this->getTotalPaye($id));
    }

    public function updateStatut($id)
    {
        $total = $this->getTotal($id);
        $totalPaye = $this->getTotalPaye($id);

        if ($totalPaye <= 0) {
            $statut = 'En attente';
        } elseif ($totalPaye < $total) {
            $statut = 'Partiellement payee';
        } else {
            $statut = 'Payee';
        }

        $stmt = $this->db->prepare('UPDATE commandes SET statut = ? WHERE id = ?');
        return $stmt->execute([$statut, $id]);
    }
}
