# 🌿 EcoNutri — Plateforme de Gestion Nutritionnelle

> Projet Web MVC PHP — Intégration Équipe

---

## 📋 Description

**EcoNutri** est une plateforme web complète dédiée à une alimentation saine et durable, développée dans le cadre d'un projet académique collaboratif à **ESPRIT** (École Supérieure Privée d'Ingénierie et de Technologies) par une équipe de trois développeurs. La plateforme vise à aider les utilisateurs à adopter de meilleures habitudes alimentaires en proposant des conseils nutritionnels personnalisés, une gestion des recettes et un blog éducatif — le tout au sein d'une seule application web unifiée.

La plateforme est construite avec une **architecture MVC PHP 8**, des bases de données MySQL, PDO pour un accès sécurisé aux données, et une séparation claire entre les contrôleurs, les modèles et les vues. L'ensemble du projet a été développé en collaboration via Git, chaque développeur travaillant sur une branche dédiée avant de fusionner dans la branche principale.

**Module 1 — Gestion des Utilisateurs & Authentification (Achref Challouf) :**
Ce module constitue le socle de la plateforme EcoNutri. Il fournit un système d'authentification complet incluant l'inscription avec validation de mot de passe fort, la connexion avec support du cookie "Se souvenir de moi", et une fonctionnalité de mot de passe oublié. Une connexion innovante par reconnaissance faciale a été implémentée avec face-api.js, permettant aux administrateurs de s'authentifier via leur webcam. Le tableau de bord admin offre des opérations CRUD complètes sur les comptes utilisateurs, incluant la recherche, le tri, le bannissement/débannissement et des statistiques détaillées. Chaque utilisateur dispose d'un profil personnalisé avec upload d'avatar. Les nouveaux inscrits reçoivent un email de bienvenue automatique via PHPMailer avec SMTP Gmail. Les fonctionnalités supplémentaires incluent une carte OpenStreetMap interactive, un générateur de QR Code carte de visite, un mode sombre/clair et un calendrier de date d'inscription.

**Module 2 — Blog & Système de Quiz (Mohamed Ouslati) :**
Ce module fournit un système complet de gestion de blog pour la publication d'articles nutritionnels. Les administrateurs peuvent créer, modifier, supprimer et programmer des articles pour une publication future. Chaque article supporte l'upload d'images, du contenu riche, le suivi des vues et des likes par adresse IP, et un quiz intégré de 3 questions accessible via un QR code unique. Un système de modération des commentaires permet aux administrateurs d'approuver, refuser ou supprimer les commentaires des utilisateurs. Le back-office blog inclut des statistiques détaillées montrant les articles les plus vus et les plus aimés avec des graphiques interactifs.

**Module 3 — Gestion des Recettes & Commandes (Eya) :**
Ce module permet aux administrateurs de gérer un catalogue de recettes et d'ingrédients alimentaires avec leurs valeurs nutritionnelles (calories, protéines, glucides, lipides). Les recettes peuvent être catégorisées, liées à des ingrédients spécifiques avec des quantités, et affichées aux utilisateurs sur le front office. Les utilisateurs peuvent passer des commandes de recettes, et les administrateurs peuvent accepter ou refuser les commandes avec des messages personnalisés. Le back-office inclut des statistiques et une interface complète de gestion des commandes.

Les trois modules partagent une interface front office unifiée, un système de navigation commun, et sont accessibles via un point d'entrée unique `index.php`. L'intégration garantit aux utilisateurs une expérience fluide et cohérente quel que soit le module avec lequel ils interagissent.

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
│   ├── AuthController.php          # Authentification
│   ├── UserController.php          # CRUD Utilisateurs
│   ├── ProfilController.php        # Gestion profil
│   ├── BlogController.php          # Blog front office
│   ├── AdminBlogController.php     # Blog back office
│   └── QuizController.php          # Système de quiz
├── Model/
│   ├── User.php                    # Modèle utilisateur
│   ├── Blog.php                    # Modèle blog
│   └── Commentaire.php             # Modèle commentaire
├── View/
│   ├── auth/                       # Login, Register, Mot de passe oublié
│   ├── frontoffice/                # Accueil, Profil, Blog
│   └── backoffice/                 # Dashboard admin, CRUD
├── recette/                        # Module recette (indépendant)
├── config/
│   ├── database.php                # Connexion PDO (Singleton)
│   └── mailer.php                  # Configuration PHPMailer
├── uploads/avatars/                # Photos de profil
└── index.php                       # Routeur principal
```

---

## 🛠️ Technologies Utilisées

- **PHP 8** — Backend MVC
- **MySQL** — Base de données
- **PDO** — Accès sécurisé aux données
- **PHPMailer 6.9.1** — Envoi d'emails SMTP
- **face-api.js** — Reconnaissance faciale
- **OpenStreetMap** — Cartographie interactive
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

**1. Cloner le dépôt :**
```bash
git clone https://github.com/achref6456/econutrismar.git
```

**2. Copier dans htdocs :**
```
C:\xampp\htdocs\econutrismar\
```

**3. Importer les bases de données dans phpMyAdmin :**
- Ouvrir `http://localhost/phpmyadmin`
- Importer `database.sql` → crée `econutrismar`
- Importer `blog.sql` → crée `econutri_db`
- Importer `recette.sql` → crée `econutri`

**4. Lancer XAMPP** (Apache + MySQL)

**5. Accéder au projet :**
```
http://localhost/econutrismar/
```

---

## 👤 Comptes de Test

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
| Recettes | `http://localhost/econutrismar/recette/views/index.php` |
| Recettes Admin | `http://localhost/econutrismar/recette/views/backoffice/index.php` |

---

## 👨‍💻 Équipe

| Module | Développeur |
|--------|-------------|
| Gestion Utilisateurs + Authentification | **Achref Challouf** |
| Blog + Quiz + Commentaires | **Mohamed Ouslati** |
| Recettes + Aliments + Commandes | **Eya** |

---

© 2026 EcoNutri — Alimentation saine & durable 🌿
