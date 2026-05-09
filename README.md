# 🌿 EcoNutri — Plateforme de Gestion Nutritionnelle

> Projet Web MVC PHP — Gestion des Utilisateurs

---

## 📋 Description

**EcoNutri** est une plateforme web dédiée à une alimentation saine et durable.
Ce module gère l'ensemble des utilisateurs de la plateforme avec un système complet d'authentification, de gestion de profil et d'administration.

---

## 🚀 Fonctionnalités

### Métiers Simples
| Fonctionnalité | Description |
|---|---|
| 🔍 **Recherche** | Recherche par nom, prénom ou email |
| 🔃 **Tri** | Tri par nom, prénom, email, rôle, date |
| 📊 **Statistiques** | Total users, admins, inscrits cette semaine |

### Métiers Avancés
| Fonctionnalité | Description |
|---|---|
| 🔒 **Mot de passe fort** | Barre de force + règles visuelles |
| 📧 **Email** | Email de bienvenue via PHPMailer SMTP Gmail |
| 🍪 **Cookies** | Remember Me — connexion persistante |
| 📅 **Calendrier** | Date d'inscription + aujourd'hui |
| 📍 **Maps** | Carte OpenStreetMap intégrée |
| 📱 **QR Code** | Carte de visite scannable |
| 🌙 **Dark/Light Mode** | Thème sombre/clair mémorisé |
| 🔐 **Reconnaissance faciale** | Connexion admin par visage (face-api.js) |

---

## 🏗️ Architecture MVC

```
econutrismar/
├── Controller/
│   ├── AuthController.php      # Authentification
│   ├── UserController.php      # CRUD Utilisateurs
│   └── ProfilController.php    # Gestion profil
├── Model/
│   └── User.php                # Modèle utilisateur
├── View/
│   ├── auth/                   # Login, Register, Forgot password
│   ├── frontoffice/            # Accueil, Profil
│   └── backoffice/             # Dashboard admin, CRUD users
├── config/
│   ├── database.php            # Connexion PDO (Singleton)
│   └── mailer.php              # Configuration PHPMailer
├── models/                     # Modèles face-api.js
├── uploads/avatars/            # Photos de profil
└── index.php                   # Routeur principal
```

---

## 🛠️ Technologies utilisées

- **PHP 8** — Backend MVC
- **MySQL** — Base de données
- **PDO** — Accès sécurisé aux données
- **PHPMailer 6.9.1** — Envoi d'emails SMTP
- **face-api.js** — Reconnaissance faciale
- **OpenStreetMap** — Cartographie
- **qrserver.com API** — Génération QR Code
- **HTML5 / CSS3 / JavaScript** — Frontend

---

## 🔐 Sécurité

- Mots de passe hashés avec **ARGON2ID**
- Requêtes préparées **PDO** (protection injection SQL)
- Échappement **htmlspecialchars()** (protection XSS)
- Vérification des rôles (admin/user)
- Validation côté serveur sur tous les formulaires

---

## ⚙️ Installation

### Prérequis
- XAMPP (Apache + MySQL + PHP 8)
- Navigateur moderne

### Étapes

**1. Cloner le repo :**
```bash
git clone https://github.com/achref6456/econutrismar.git
```

**2. Copier dans htdocs :**
```
C:\xampp\htdocs\econutrismar\
```

**3. Importer la base de données :**
- Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
- Créer une base `econutrismar`
- Importer `database.sql`

**4. Lancer XAMPP** (Apache + MySQL)

**5. Accéder au projet :**
```
http://localhost/econutrismar/index.php?page=frontoffice
```

---

## 👤 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@econutri.com | Admin1234 |

---

## 📱 Liens

| Page | URL |
|------|-----|
| Front Office | `/index.php?page=frontoffice` |
| Back Office | `/index.php?page=backoffice` |
| Connexion | `/index.php?page=login` |
| Reconnaissance faciale | `/index.php?page=face-login` |

---

## 👨‍💻 Auteur

**Achref Challouf** — Module Gestion Utilisateurs

---

© 2026 EcoNutri — Alimentation saine & durable 🌿
