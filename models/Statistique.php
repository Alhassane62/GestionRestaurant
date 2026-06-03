<?php

class Statistique
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getDashboardStats()
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM plats) AS total_plats,
                    (SELECT COUNT(*) FROM clients) AS total_clients,
                    (SELECT COUNT(*) FROM commandes) AS total_commandes,
                    (SELECT COUNT(*) FROM commandes WHERE CAST(date_commande AS DATE) = CAST(GETDATE() AS DATE)) AS commandes_jour,
                    (SELECT COALESCE(SUM(montant_paye), 0) FROM paiements) AS recettes_totales,
                    (SELECT COALESCE(SUM(montant_paye), 0) FROM paiements WHERE CAST(date_paiement AS DATE) = CAST(GETDATE() AS DATE)) AS recettes_jour";
        return $this->db->query($sql)->fetch();
    }

    public function getPlatsPlusCommandes()
    {
        $sql = "SELECT TOP 8 p.nom, c.nom AS categorie_nom,
                       SUM(cp.quantite) AS quantite_totale,
                       SUM(cp.quantite * cp.prix_unitaire) AS total_vente
                FROM commande_plats cp
                INNER JOIN plats p ON p.id = cp.plat_id
                INNER JOIN categories c ON c.id = p.categorie_id
                GROUP BY p.id, p.nom, c.nom
                ORDER BY quantite_totale DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getRecettesParJour()
    {
        $sql = "SELECT TOP 10
                    CONVERT(VARCHAR(10), CAST(date_paiement AS DATE), 23) AS jour,
                    SUM(montant_paye) AS total
                FROM paiements
                GROUP BY CAST(date_paiement AS DATE)
                ORDER BY jour";
        return $this->db->query($sql)->fetchAll();
    }

    public function getCommandesParStatut()
    {
        $sql = "SELECT statut, COUNT(*) AS total
                FROM commandes
                GROUP BY statut
                ORDER BY statut";
        return $this->db->query($sql)->fetchAll();
    }

    public function getRecettes()
    {
        $sql = "SELECT
                    COALESCE(SUM(montant_paye), 0) AS total,
                    COALESCE(SUM(CASE WHEN CAST(date_paiement AS DATE) = CAST(GETDATE() AS DATE) THEN montant_paye ELSE 0 END), 0) AS aujourd_hui
                FROM paiements";
        return $this->db->query($sql)->fetch();
    }

    public function getCommandesDuJour()
    {
        $sql = "SELECT co.id, co.date_commande, co.statut, cl.nom AS client_nom,
                       COALESCE((SELECT SUM(cp.quantite * cp.prix_unitaire) FROM commande_plats cp WHERE cp.commande_id = co.id), 0) AS total_commande,
                       COALESCE((SELECT SUM(pa.montant_paye) FROM paiements pa WHERE pa.commande_id = co.id), 0) AS total_paye
                FROM commandes co
                INNER JOIN clients cl ON cl.id = co.client_id
                WHERE CAST(co.date_commande AS DATE) = CAST(GETDATE() AS DATE)
                ORDER BY co.date_commande DESC";
        return $this->db->query($sql)->fetchAll();
    }
}
