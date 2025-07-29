-- Բազայի ստեղծում
DROP DATABASE IF EXISTS VillageGreen;

CREATE DATABASE VillageGreen;

USE VillageGreen;

-- CATEGORIE
CREATE TABLE Categorie (
    Id_Categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    Id_Categorie_Parent INT,
    FOREIGN KEY (Id_Categorie_Parent) REFERENCES Categorie (Id_Categorie)
);

-- FOURNISSEUR
CREATE TABLE Fournisseur (
    Id_Fournisseur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    coordonnees VARCHAR(255)
);

-- COMMERCIAL
CREATE TABLE Commercial (
    Id_Commercial VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

-- CLIENT
CREATE TABLE Client (
    Id_Client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    type_client ENUM('particulier', 'professionnel') NOT NULL,
    coefficient DECIMAL(5,2),
    adresse_facturation VARCHAR(255),
    cp_facturation VARCHAR(10),
    ville_facturation VARCHAR(255),
    adresse_livraison VARCHAR(255),
    cp_livraison VARCHAR(10),
    ville_livraison VARCHAR(255),
    prenom VARCHAR(255),
    telephone VARCHAR(20),
    Id_Commercial VARCHAR(255),
    FOREIGN KEY (Id_Commercial) REFERENCES Commercial (Id_Commercial) ON DELETE SET NULL
);

-- UTILISATEUR
CREATE TABLE Utilisateur (
    Id_Utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    mot_de_passe_h TEXT NOT NULL,
    role ENUM('admin', 'commercial', 'client') NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    actif BOOLEAN,
    Id_Commercial VARCHAR(255),
    Id_Client INT,
    FOREIGN KEY (Id_Commercial) REFERENCES Commercial (Id_Commercial) ON DELETE SET NULL,
    FOREIGN KEY (Id_Client) REFERENCES Client (Id_Client) ON DELETE SET NULL
);

-- PRODUIT
CREATE TABLE Produit (
    Id_Produit INT AUTO_INCREMENT PRIMARY KEY,
    libelle_long VARCHAR(255) NOT NULL,
    libelle_court VARCHAR(100),
    prix_achat DECIMAL(15, 2),
    photo VARCHAR(255),
    reference_fournisseur VARCHAR(50),
    actif BOOLEAN,
    publie BOOLEAN,
    creat_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    Id_Categorie INT,
    FOREIGN KEY (Id_Categorie) REFERENCES Categorie (Id_Categorie) ON DELETE SET NULL
);

-- COMMANDE
CREATE TABLE Commande (
    Id_Commande INT AUTO_INCREMENT PRIMARY KEY,
    date_commande DATE,
    statut ENUM('en attente', 'validée', 'expédiée', 'annulée', 'confirmée') NOT NULL,
    reduction INT,
    paiement INT,
    adresse_livraison VARCHAR(255),
    cp_livraison VARCHAR(10),
    ville_livraison VARCHAR(255),
    adresse_facturation VARCHAR(255),
    cp_facturation VARCHAR(10),
    ville_facturation VARCHAR(255),
    montant_total_ht DECIMAL(15, 2),
    Id_Client INT,
    FOREIGN KEY (Id_Client) REFERENCES Client (Id_Client) ON DELETE SET NULL
);

-- BON DE LIVRAISON
CREATE TABLE BonLivraison (
    Id_BonLivraison INT AUTO_INCREMENT PRIMARY KEY,
    date_livraison DATE,
    Id_Commande INT,
    FOREIGN KEY (Id_Commande) REFERENCES Commande (Id_Commande) ON DELETE CASCADE
);

-- FACTURE
CREATE TABLE Facture (
    Id_Facture INT AUTO_INCREMENT PRIMARY KEY,
    date_facture DATE,
    montant_total_ht DECIMAL(15, 2),
    paiement_effect DECIMAL(15, 2),
    Id_Commande INT,
    FOREIGN KEY (Id_Commande) REFERENCES Commande (Id_Commande) ON DELETE CASCADE
);

-- PAIEMENT
CREATE TABLE Paiement (
    Id_Paiement INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('CB', 'virement', 'espèces') NOT NULL,
    date_paiement DATETIME,
    montant DECIMAL(10, 2),
    valide BOOLEAN,
    Id_Commande INT,
    FOREIGN KEY (Id_Commande) REFERENCES Commande (Id_Commande) ON DELETE CASCADE
);

-- LIGNE COMMANDE
CREATE TABLE LigneCommande (
    Id_LigneCommande INT AUTO_INCREMENT PRIMARY KEY,
    quantite INT,
    prix_unit_ht DECIMAL(10, 2),
    Id_Commande INT,
    Id_Produit INT,
    FOREIGN KEY (Id_Commande) REFERENCES Commande (Id_Commande) ON DELETE CASCADE,
    FOREIGN KEY (Id_Produit) REFERENCES Produit (Id_Produit) ON DELETE CASCADE
);

-- LIVRAISON PRODUIT
CREATE TABLE LivraisonProduit (
    Id_Produit INT,
    Id_BonLivraison INT,
    quantite_livree INT,
    PRIMARY KEY (Id_Produit, Id_BonLivraison),
    FOREIGN KEY (Id_Produit) REFERENCES Produit (Id_Produit) ON DELETE CASCADE,
    FOREIGN KEY (Id_BonLivraison) REFERENCES BonLivraison (Id_BonLivraison) ON DELETE CASCADE
);

-- FOURNISSEUR - CATEGORIE
CREATE TABLE FournisseurCategorie (
    Id_Categorie INT,
    Id_Fournisseur INT,
    PRIMARY KEY (Id_Categorie, Id_Fournisseur),
    FOREIGN KEY (Id_Categorie) REFERENCES Categorie (Id_Categorie) ON DELETE CASCADE,
    FOREIGN KEY (Id_Fournisseur) REFERENCES Fournisseur (Id_Fournisseur) ON DELETE CASCADE
);

-- FOURNISSEUR - PRODUIT
CREATE TABLE FournisseurProduit (
    Id_Produit INT,
    Id_Fournisseur INT,
    PRIMARY KEY (Id_Produit, Id_Fournisseur),
    FOREIGN KEY (Id_Produit) REFERENCES Produit (Id_Produit) ON DELETE CASCADE,
    FOREIGN KEY (Id_Fournisseur) REFERENCES Fournisseur (Id_Fournisseur) ON DELETE CASCADE
);
