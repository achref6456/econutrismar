# 🌿 EcoNutri — Plateforme de Gestion Nutritionnelle

> Projet Web MVC PHP — Gestion des Utilisateurs

---

## 📋 Description

**EcoNutri** is a comprehensive web platform dedicated to healthy and sustainable nutrition, developed as a team academic project at **ESPRIT** (École Supérieure Privée d'Ingénierie et de Technologies). The platform brings together three integrated modules built by a team of three developers, each responsible for a distinct feature set.

The first module, developed by **Achref Challouf**, handles complete **user management and authentication**: registration, login, profile management, admin dashboard with full CRUD operations, face recognition login using face-api.js, email notifications via PHPMailer, and advanced features such as dark/light mode, QR code generation, and interactive maps.

The second module, developed by **Mohamed Ouslati**, covers the **blog system**: publishing and managing articles with rich content, a comment moderation system, an integrated quiz with QR code access for each article, scheduled publication, view and like tracking, and a full backoffice for blog administration.

The third module covers **recipe management**: creating and managing recipes with ingredients and nutritional values, categorizing food items, handling user orders, and providing a dedicated backoffice with statistics and order management.

All three modules are fully integrated under a single MVC PHP application with a unified routing system, shared authentication, and a consistent user interface. The integration was managed through Git with feature branches merged into the main branch after validation.

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
| Admin | admin@econutri.com | password |

---

## 📱 Liens

| Page | URL |
|------|-----|
| Front Office | `http://localhost/econutrismar/` |
| Back Office | `http://localhost/econutrismar/index.php?page=backoffice` |
| Connexion | `http://localhost/econutrismar/index.php?page=login` |
| Reconnaissance faciale | `http://localhost/econutrismar/index.php?page=face-login` |
| Blog | `http://localhost/econutrismar/index.php?page=blog` |
| Blog Admin | `http://localhost/econutrismar/index.php?page=admin_blog` |
| Recette | `http://localhost/econutrismar/recette/views/index.php` |
| Recette Admin | `http://localhost/econutrismar/recette/views/backoffice/index.php` |

---

## 👨‍💻 Équipe

| Module | Développeur |
|--------|-------------|
| Gestion Utilisateurs + Auth | **Achref Challouf** |
| Blog + Quiz + Commentaires | **Mohamed** |
| Recettes + Aliments + Commandes | **Camarade** |

---

© 2026 EcoNutri — Alimentation saine & durable 🌿
