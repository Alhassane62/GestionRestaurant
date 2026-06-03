USE master;
GO

CREATE DATABASE GestionRestaurant;
GO

CREATE LOGIN restaurant WITH PASSWORD = 'alhassane123';
GO

USE GestionRestaurant;
GO

CREATE USER restaurant FOR LOGIN restaurant;
GO

ALTER ROLE db_owner ADD MEMBER restaurant;
GO

CREATE TABLE categories (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL UNIQUE
);
GO

CREATE TABLE plats (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom NVARCHAR(150) NOT NULL,
    description NVARCHAR(255) NULL,
    prix DECIMAL(10,2) NOT NULL,
    categorie_id INT NOT NULL,

    CONSTRAINT FK_plats_categories
        FOREIGN KEY (categorie_id) REFERENCES categories(id),

    CONSTRAINT CK_plats_prix
        CHECK (prix >= 0)
);
GO

CREATE TABLE clients (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom NVARCHAR(100) NOT NULL,
    telephone NVARCHAR(30) NOT NULL
);
GO

CREATE TABLE commandes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    client_id INT NOT NULL,
    date_commande DATETIME NOT NULL,
    statut NVARCHAR(50) NOT NULL DEFAULT 'En attente',

    CONSTRAINT FK_commandes_clients
        FOREIGN KEY (client_id) REFERENCES clients(id),

    CONSTRAINT CK_commandes_statut
        CHECK (statut IN ('En attente', 'Payee', 'Partiellement payee', 'Annulee'))
);
GO

CREATE TABLE commande_plats (
    id INT IDENTITY(1,1) PRIMARY KEY,
    commande_id INT NOT NULL,
    plat_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,

    CONSTRAINT FK_commande_plats_commandes
        FOREIGN KEY (commande_id) REFERENCES commandes(id),

    CONSTRAINT FK_commande_plats_plats
        FOREIGN KEY (plat_id) REFERENCES plats(id),

    CONSTRAINT CK_commande_plats_quantite
        CHECK (quantite > 0),

    CONSTRAINT CK_commande_plats_prix
        CHECK (prix_unitaire >= 0)
);
GO

CREATE TABLE paiements (
    id INT IDENTITY(1,1) PRIMARY KEY,
    commande_id INT NOT NULL,
    montant_paye DECIMAL(10,2) NOT NULL,
    date_paiement DATETIME NOT NULL,

    CONSTRAINT FK_paiements_commandes
        FOREIGN KEY (commande_id) REFERENCES commandes(id),

    CONSTRAINT CK_paiements_montant
        CHECK (montant_paye > 0)
);
GO