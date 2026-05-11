# 🌿 EcoNutri — Nutritional Management Platform

> PHP MVC Web Project — Team Integration

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

## 🚀 Features

### Basic Features
| Feature | Description |
|---|---|
| 🔍 **Search** | Search users by name, first name or email |
| 🔃 **Sort** | Sort by name, first name, email, role, date |
| 📊 **Statistics** | Total users, admins, registered this week |

### Advanced Features
| Feature | Description |
|---|---|
| 🔒 **Strong Password** | Strength bar + visual rules |
| 📧 **Email** | Welcome email via PHPMailer SMTP Gmail |
| 🍪 **Cookies** | Remember Me — persistent login |
| 📅 **Calendar** | Registration date + today |
| 📍 **Maps** | Integrated OpenStreetMap |
| 📱 **QR Code** | Scannable business card |
| 🌙 **Dark/Light Mode** | Saved theme toggle |
| 🔐 **Face Recognition** | Admin login by face (face-api.js) |

---

## 🏗️ MVC Architecture

```
econutrismar/
├── Controller/
│   ├── AuthController.php          # Authentication
│   ├── UserController.php          # User CRUD
│   ├── ProfilController.php        # Profile management
│   ├── BlogController.php          # Blog front office
│   ├── AdminBlogController.php     # Blog back office
│   └── QuizController.php          # Quiz system
├── Model/
│   ├── User.php                    # User model
│   ├── Blog.php                    # Blog model
│   └── Commentaire.php             # Comment model
├── View/
│   ├── auth/                       # Login, Register, Forgot password
│   ├── frontoffice/                # Home, Profile, Blog
│   └── backoffice/                 # Admin dashboard, CRUD
├── recette/                        # Recipe module (independent)
├── config/
│   ├── database.php                # PDO connection (Singleton)
│   └── mailer.php                  # PHPMailer configuration
├── uploads/avatars/                # Profile pictures
└── index.php                       # Main router
```

---

## 🛠️ Technologies Used

- **PHP 8** — MVC Backend
- **MySQL** — Database
- **PDO** — Secure data access
- **PHPMailer 6.9.1** — SMTP email sending
- **face-api.js** — Face recognition
- **OpenStreetMap** — Interactive map
- **qrserver.com API** — QR Code generation
- **HTML5 / CSS3 / JavaScript** — Frontend

---

## 🔐 Security

- Passwords hashed with **ARGON2ID**
- **PDO** prepared statements (SQL injection protection)
- **htmlspecialchars()** escaping (XSS protection)
- Role-based access control (admin/user)
- Server-side validation on all forms

---

## ⚙️ Installation

### Requirements
- XAMPP (Apache + MySQL + PHP 8)
- Modern browser

### Steps

**1. Clone the repository:**
```bash
git clone https://github.com/achref6456/econutrismar.git
```

**2. Copy to htdocs:**
```
C:\xampp\htdocs\econutrismar\
```

**3. Import databases in phpMyAdmin:**
- Open `http://localhost/phpmyadmin`
- Import `database.sql` → creates `econutrismar`
- Import `blog.sql` → creates `econutri_db`
- Import `recette.sql` → creates `econutri`

**4. Start XAMPP** (Apache + MySQL)

**5. Access the project:**
```
http://localhost/econutrismar/
```

---

## 👤 Test Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@econutri.com | password |

---

## 📱 Links

| Page | URL |
|------|-----|
| Front Office | `http://localhost/econutrismar/` |
| Back Office | `http://localhost/econutrismar/index.php?page=backoffice` |
| Login | `http://localhost/econutrismar/index.php?page=login` |
| Face Login | `http://localhost/econutrismar/index.php?page=face-login` |
| Blog | `http://localhost/econutrismar/index.php?page=blog` |
| Blog Admin | `http://localhost/econutrismar/index.php?page=admin_blog` |
| Recipes | `http://localhost/econutrismar/recette/views/index.php` |
| Recipes Admin | `http://localhost/econutrismar/recette/views/backoffice/index.php` |

---

## 👨‍💻 Team

| Module | Developer |
|--------|-----------|
| User Management + Authentication | **Achref Challouf** |
| Blog + Quiz + Comments | **Mohamed Ouslati** |
| Recipes + Ingredients + Orders | **Eya** |

---

© 2026 EcoNutri — Healthy & Sustainable Nutrition 🌿
