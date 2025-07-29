# 🎸 Village Green - Gestion Commerciale & E-Commerce

Ce projet est réalisé dans le cadre du Fil Rouge CDA. Il s'agit d'une application web, mobile et back-office pour la gestion commerciale de l'entreprise **Village Green**, spécialisée dans la distribution de matériel musical.

## 🚀 Objectifs

- Digitalisation du processus de vente (catalogue, panier, commande, facturation)
- Mise en place d’un site e-commerce pour particuliers et professionnels
- Application mobile pour les clients particuliers
- Tableau de bord analytique pour usage interne

---

## 📦 Architecture du Projet

- **Frontend Web** : Symfony (Twig), HTML/CSS/JS
- **Backend API** : Symfony + JWT Auth
- **Mobile** : Flutter ou React Native (selon choix)
- **Base de données** : MariaDB (via DBeaver)
- **Conteneurisation** : Docker + Docker Compose
- **Outils** : Git, Figma (maquettes), Draw.io (modélisation)

---

## 📁 Structure du Répertoire

```
📦 village-green/
├── app/                   # Code Symfony (MVC)
├── public/                # Fichiers publics (assets, images, etc.)
├── mobile-app/            # Code de l'application mobile
├── sql/                   # Scripts SQL : création, données, vues
│   ├── schema.sql
│   ├── data.sql
│   ├── views.sql
├── docker/
│   ├── docker-compose.yml
│   ├── php/
│   ├── mariadb/
│   └── maildev/
├── docs/
│   ├── dictionnaire_donnees.md
│   ├── regles_de_gestion.md
│   ├── erd.png
│   └── maquettes/
├── tests/
├── README.md
```

---

## 🛠️ Installation

### Prérequis

- Docker + Docker Compose
- Git
- Composer
- Node.js + Yarn (pour les assets Symfony)

### Démarrage

```bash
git clone https://github.com/ton-projet/village-green.git
cd village-green
docker-compose up --build
```

Accès :

- Frontend : `http://localhost:8000`
- PHPMyAdmin (optionnel) : `http://localhost:8080`
- MailDev : `http://localhost:1080`

---

## 📊 Base de Données

- Modélisation relationnelle (.erd.png)
- Scripts SQL :
  - `schema.sql` : création des tables
  - `data.sql` : insertion de données fictives
  - `views.sql` : création des vues métier

---

## 📚 Documentation

- Dictionnaire de données : `docs/dictionnaire_donnees.md`
- Règles de gestion : `docs/regles_de_gestion.md`
- Diagrammes UML : `docs/uml/`
- Maquettes Figma : `docs/maquettes/`

---

## 🔐 Authentification

- Frontend : Session utilisateur (Symfony)
- Backend/API : JWT Token pour les appels sécurisés

---

## 📈 Tableau de Bord (Back Office)

- CA par mois / par fournisseur
- Top produits (commandés / rentables)
- Top clients
- Répartition CA
- Commandes en cours

---

## 📱 Application Mobile (Particuliers)

- Navigation dans le catalogue
- Connexion + historique des commandes
- Responsive et fluide

---

## 👨‍💻 Auteurs

- [Ton Nom] – Développement FullStack
- [Nom du Formateur / Équipe]

---

## 📄 Licence

Ce projet est réalisé dans un cadre pédagogique.
# VillageGreen
