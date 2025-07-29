-- Categories
INSERT INTO Categorie (nom, image, Id_Categorie_Parent) VALUES
    ('Instruments', 'instruments.jpg', NULL),
    ('Cordes', 'cordes.jpg', 1),
    ('Percussions', 'percussions.jpg', 1),
    ('Accessoires', 'accessoires.jpg', NULL);

-- Fournisseurs
INSERT INTO Fournisseur (nom, coordonnees) VALUES
    ('Yamaha France', 'contact@yamaha.fr'),
    ('Roland Europe', 'info@roland.fr'),
    ('Fender France', 'support@fender.fr');

-- Commerciaux
INSERT INTO Commercial (Id_Commercial, nom, email) VALUES
    ('C001', 'Jean Dupont', 'j.dupont@vg.fr'),
    ('C002', 'Sophie Martin', 's.martin@vg.fr');

-- Clients
INSERT INTO Client (
    nom, type_client, coefficient,
    adresse_facturation, cp_facturation, ville_facturation,
    adresse_livraison, cp_livraison, ville_livraison,
    prenom, telephone, Id_Commercial
) VALUES
    ('Entreprise Durand', 'professionnel', 15,
     '12 Rue Lafayette', '75009', 'Paris',
     '12 Rue Lafayette', '75009', 'Paris',
     'Luc', '0145789632', 'C001'),
    ('Claire Bernard', 'particulier', 0,
     '5 Avenue Victor Hugo', '69006', 'Lyon',
     '5 Avenue Victor Hugo', '69006', 'Lyon',
     'Claire', '0478123456', 'C002');

-- Utilisateurs
INSERT INTO Utilisateur (
    email, mot_de_passe_h, role,
    date_creation, actif, Id_Commercial, Id_Client
) VALUES
    ('admin@vg.fr', 'motdepassehash1', 'admin', NOW(), TRUE, NULL, NULL),
    ('j.dupont@vg.fr', 'motdepassehash2', 'commercial', NOW(), TRUE, 'C001', NULL),
    ('claire.bernard@gmail.com', 'motdepassehash3', 'client', NOW(), TRUE, NULL, 2);

-- Produits
INSERT INTO Produit (
    libelle_long, libelle_court, prix_achat, photo,
    reference_fournisseur, actif, publie,
    creat_at, update_at, Id_Categorie
) VALUES
    ('Guitare acoustique Yamaha F310', 'Yamaha F310', 199.99, 'guitare1.jpg',
     'YAM-F310', TRUE, TRUE, NOW(), NOW(), 2),
    ('Batterie électronique Roland TD-1K', 'Roland TD-1K', 699.99, 'batterie1.jpg',
     'ROL-TD1K', TRUE, TRUE, NOW(), NOW(), 3);

-- Commande
INSERT INTO Commande (
    date_commande, statut, reduction, paiement,
    adresse_livraison, cp_livraison, ville_livraison,
    adresse_facturation, cp_facturation, ville_facturation,
    montant_total_ht, Id_Client
) VALUES (
    '2025-06-15', 'confirmée', 0, 1,
    '5 Avenue Victor Hugo', '69006', 'Lyon',
    '5 Avenue Victor Hugo', '69006', 'Lyon',
    199.99, 2
);

-- LigneCommande
INSERT INTO LigneCommande (
    quantite, prix_unit_ht, Id_Commande, Id_Produit
) VALUES (
    1, 199.99, 1, 1
);

-- Bon de livraison
INSERT INTO BonLivraison (date_livraison, Id_Commande) VALUES
    ('2025-06-16', 1);

-- LivraisonProduit
INSERT INTO LivraisonProduit (
    Id_Produit, Id_BonLivraison, quantite_livree
) VALUES (
    1, 1, 1
);

-- Facture
INSERT INTO Facture (
    date_facture, montant_total_ht, paiement_effect, Id_Commande
) VALUES (
    '2025-06-17', 199.99, 199.99, 1
);

-- Paiement
INSERT INTO Paiement (
    type, date_paiement, montant, valide, Id_Commande
) VALUES (
    'CB', '2025-06-17 12:00:00', 199.99, 1, 1
);

-- FournisseurCategorie
INSERT INTO FournisseurCategorie (Id_Categorie, Id_Fournisseur) VALUES
    (2, 1),
    (3, 2);

-- FournisseurProduit
INSERT INTO FournisseurProduit (Id_Produit, Id_Fournisseur) VALUES
    (1, 1);
