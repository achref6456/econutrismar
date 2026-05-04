<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/User.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ../../index.php'); exit; }

$userModel = new User();
$user = $userModel->findById($id);
if (!$user) { header('Location: ../../index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri — <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --green-dark:#2d6a1f; --green-main:#4a9e30; --green-light:#7ec44f;
      --green-pale:#e8f5e1; --orange:#f07c1b; --white:#fff; --grey:#666;
      --border:#e4eed9; --bg:#f2f8ee;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body {
      font-family:"DM Sans",sans-serif;
      min-height:100vh;
      background:linear-gradient(160deg, var(--green-pale) 0%, #d4edbc 55%, #f5fff0 100%);
      display:grid; place-items:center; padding:1.5rem;
    }
    .card {
      background:var(--white);
      border-radius:28px;
      box-shadow:0 20px 60px rgba(45,106,31,0.2);
      width:min(380px, 100%);
      overflow:hidden;
    }
    .card-head {
      background:linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding:2rem 2rem 1.5rem;
      text-align:center;
    }
    .avatar {
      width:80px; height:80px;
      border-radius:50%;
      object-fit:cover;
      border:4px solid rgba(255,255,255,0.5);
      margin-bottom:0.8rem;
    }
    .avatar-placeholder {
      width:80px; height:80px;
      border-radius:50%;
      background:rgba(255,255,255,0.2);
      border:4px solid rgba(255,255,255,0.5);
      display:grid; place-items:center;
      font-size:2rem; color:var(--white);
      font-family:"Playfair Display",serif;
      font-weight:700;
      margin:0 auto 0.8rem;
    }
    .card-head h1 {
      font-family:"Playfair Display",serif;
      font-size:1.4rem; color:var(--white);
      margin-bottom:0.4rem;
    }
    .role-badge {
      font-size:0.8rem;
      color:rgba(255,255,255,0.9);
      background:rgba(255,255,255,0.2);
      padding:0.25rem 0.9rem;
      border-radius:50px;
      display:inline-block;
    }
    .card-body { padding:1.8rem 1.8rem 1.2rem; }
    .info-item {
      display:flex; align-items:center; gap:0.9rem;
      padding:0.85rem 0;
      border-bottom:1px solid var(--border);
    }
    .info-item:last-of-type { border-bottom:none; }
    .info-icon {
      width:40px; height:40px;
      border-radius:12px;
      background:var(--green-pale);
      display:grid; place-items:center;
      font-size:1.1rem; flex-shrink:0;
    }
    .info-label { font-size:0.7rem; color:var(--grey); margin-bottom:0.1rem; }
    .info-val { font-weight:600; color:#111; font-size:0.88rem; }
    .card-foot {
      background:linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding:1rem 1.8rem;
      display:flex; align-items:center; justify-content:space-between;
    }
    .logo-wrap { display:flex; align-items:center; gap:0.5rem; }
    .logo-icon {
      width:32px; height:32px;
      background:rgba(255,255,255,0.2);
      border-radius:8px;
      display:grid; place-items:center;
      font-size:1.1rem;
    }
    .logo-text {
      font-family:"Playfair Display",serif;
      font-size:1.1rem; color:var(--white); font-weight:700;
    }
    .logo-text span { color:var(--orange); }
    .tagline { font-size:0.7rem; color:rgba(255,255,255,0.7); }
  </style>
</head>
<body>
<div class="card">
  <div class="card-head">
    <?php if (!empty($user['avatar'])): ?>
      <img src="http://192.168.1.8/econutrismar/uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" class="avatar" />
    <?php else: ?>
      <div class="avatar-placeholder"><?= strtoupper(substr($user['prenom'], 0, 1)) ?></div>
    <?php endif; ?>
    <h1><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
    <span class="role-badge"><?= $user['role'] === 'admin' ? '⭐ Administrateur' : '👤 Utilisateur' ?></span>
  </div>

  <div class="card-body">
    <div class="info-item">
      <div class="info-icon">📧</div>
      <div>
        <div class="info-label">Adresse email</div>
        <div class="info-val"><?= htmlspecialchars($user['email']) ?></div>
      </div>
    </div>
    <div class="info-item">
      <div class="info-icon">📅</div>
      <div>
        <div class="info-label">Membre depuis</div>
        <div class="info-val"><?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
      </div>
    </div>
    <div class="info-item">
      <div class="info-icon">🌿</div>
      <div>
        <div class="info-label">Plateforme</div>
        <div class="info-val">EcoNutri</div>
      </div>
    </div>
  </div>

  <div class="card-foot">
    <div class="logo-wrap">
      <div class="logo-icon">🌿</div>
      <span class="logo-text">Eco<span>Nutri</span></span>
    </div>
    <div class="tagline">Alimentation saine & durable</div>
  </div>
</div>
</body>
</html>
