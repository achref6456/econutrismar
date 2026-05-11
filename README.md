# 🌿 EcoNutri — Plateforme de Gestion Nutritionnelle

> Projet Web MVC PHP — Gestion des Utilisateurs

---

## 📋 Description

**EcoNutri** is a comprehensive web platform dedicated to healthy and sustainable nutrition, developed as a collaborative academic project at **ESPRIT** (École Supérieure Privée d'Ingénierie et de Technologies) by a team of three developers. The platform aims to help users adopt healthier eating habits by providing personalized nutritional guidance, recipe management, and educational blog content — all within a single, unified web application.

The platform is built using a **PHP 8 MVC architecture** with MySQL databases, PDO for secure data access, and a clean separation between controllers, models, and views. The entire project was developed collaboratively using Git, with each developer working on a dedicated feature branch before merging into the main branch.

**Module 1 — User Management & Authentication (Achref Challouf):**
This module forms the backbone of the EcoNutri platform. It provides a complete authentication system including user registration with strong password validation, login with Remember Me cookie support, and a forgot password feature. An innovative face recognition login was implemented using face-api.js, allowing administrators to authenticate using their webcam. The admin dashboard offers full CRUD operations on user accounts, including search, sort, ban/unban, and detailed statistics. Each user has a personalized profile with avatar upload. New users receive an automated welcome email sent via PHPMailer with SMTP Gmail. Additional features include an interactive OpenStreetMap, a QR code business card generator, a dark/light mode toggle, and a registration date calendar.

**Module 2 — Blog & Quiz System (Mohamed Ouslati):**
This module provides a complete blog management system for publishing nutritional articles. Administrators can create, edit, delete, and schedule articles for future publication. Each article supports image uploads, rich text content, view and like tracking per IP address, and an integrated 3-question quiz accessible via a unique QR code. A comment moderation system allows administrators to approve, reject, or delete user comments. The blog backoffice includes detailed statistics showing top-viewed and top-liked articles with interactive charts.

**Module 3 — Recipe & Order Management:**
This module allows administrators to manage a catalog of recipes and food ingredients with nutritional values (calories, proteins, carbohydrates, lipids). Recipes can be categorized, linked to specific ingredients with quantities, and displayed to users on the front office. Users can place orders for recipes, and administrators can accept or reject orders with personalized messages. The backoffice includes statistics and a full order management interface.

All three modules share a unified front office interface, a common navigation system, and are accessible through a single entry point at `index.php`. The integration ensures that users experience a seamless, cohesive platform regardless of which module they interact with.

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
