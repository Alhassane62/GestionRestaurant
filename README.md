# GestionRestaurant
Ce projet consiste en le développement d'une application de gestion complète pour un établissement de restauration. Conçue en PHP pur, l'application permet de centraliser la gestion du menu, le suivi des commandes clients et l'analyse des revenus journaliers.
🎯 Contexte et Objectifs

L'objectif est d'automatiser les processus métier d'un restaurant pour gagner en productivité.
    Efficacité : Réduction du temps de prise de commande.
    Visibilité : Suivi en temps réel des paiements et du chiffre d'affaires.
    Fidélisation : Centralisation des coordonnées clients et de leur historique.

Fonctionnalités Implémentées
🍽️ Gestion du Menu (Plats)

    CRUD Complet : Enregistrer, modifier et classer les plats.
    Tarification : Gestion dynamique des prix.
    Catégories : Organisation par type (Entrées, Plats, Desserts, Boissons).

📝 Gestion des Commandes

    Interface de saisie : Sélection des plats commandés par table ou par client.
    Suivi des paiements : Distinction entre les commandes payées et en attente.

Gestion des Clients

    Fiche Client : Enregistrement du nom et du téléphone.
    Historique : Consultation des anciennes commandes pour chaque client.

📊 Statistiques & Rapports

    Top Ventes : Visualisation des plats les plus populaires.
    Comptabilité : Calcul automatique des recettes totales.
    Vue Journalière : Liste filtrée des commandes du jour.

🏗️ Architecture du Projet
Le projet suit une structure orientée procédure ou MVC simplifié en PHP pur :
graph TD
    A[Navigateur] -->|Requêtes HTTP| B[index.php / Controllers]
    B -->|Inclusion| C[Includes / Config]
    B -->|Requêtes SQL| D[(Base de Données MySQL)]
    D -->|Résultats| B
    B -->|Rendu| E[Vues / Templates HTML]

Structure des dossiers
Plaintext
.
├── config/             # Connexion à la base de données (db.php)
├── includes/           # Fonctions réutilisables et composants (header, footer)
├── modules/
│   ├── plats/          # Logique de gestion des plats
│   ├── commandes/      # Logique de gestion des commandes
│   └── clients/        # Logique de gestion des clients
├── assets/             # Fichiers statiques (CSS, JS, Images)
├── sql/                # Scripts de création de la base de données
└── index.php           # Point d'entrée principal

Installation et Configuration
  Clonage du projet :
  
    Bash
    git clone https://github.com/Alhassane62/gestion-restaurant-php.git

Base de données :

    Importer le fichier sql/database.sql dans votre interface phpMyAdmin.
    Configurer les accès dans config/db.php.

Déploiement local :

    Placer le dossier du projet dans le répertoire htdocs (XAMPP) ou www (WAMP).
    Lancer votre serveur Apache et MySQL.
    Accéder à l'URL : http://localhost/gestion-restaurant-php
Sécurité

    Utilisation de requêtes préparées (PDO) pour prévenir les injections SQL.
    Validation et nettoyage des données entrantes (htmlspecialchars, filter_var).

📈 Perspectives d'Amélioration

    Ajout d'un système d'authentification (Espace Admin / Serveur).
    Génération de factures en PDF (via la librairie FPDF).
    Export des statistiques au format Excel/CSV.
  
