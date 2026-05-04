<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Statistiques Utilisateurs</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root{--green-dark:#2d6a1f;--green-main:#4a9e30;--green-light:#7ec44f;--green-pale:#e8f5e1;--orange:#f07c1b;--black:#111;--grey:#666;--grey-light:#999;--white:#fff;--border:#e4eed9;--sidebar-bg:#0e2a08;--sidebar-w:260px;--topbar-h:68px;--bg:#f2f8ee;--red:#e53935;--red-light:#fdecea;--blue:#1565c0;--blue-light:#e3f0ff;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:"DM Sans",sans-serif;background:var(--bg);color:var(--black);display:flex;min-height:100vh;}
    .sidebar{width:var(--sidebar-w);background:var(--sidebar-bg);min-height:100vh;position:fixed;left:0;top:0;display:flex;flex-direction:column;z-index:50;}
    .sidebar-logo{padding:1.4rem 1.6rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem;text-decoration:none;}
    .logo-icon{width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:10px;display:grid;place-items:center;border:1px solid rgba(255,255,255,0.2);font-size:1.2rem;}
    .logo-text{font-family:"Playfair Display",serif;font-size:1.35rem;color:var(--white);}
    .logo-text span{color:var(--orange);}
    .sidebar-section{padding:1.2rem 0.9rem 0.4rem;}
    .sidebar-label{font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;padding:0 0.7rem;margin-bottom:0.5rem;}
    .nav-item{display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0.9rem;border-radius:10px;text-decoration:none;color:rgba(255,255,255,0.6);font-size:0.88rem;font-weight:500;margin-bottom:0.15rem;transition:background 0.2s;}
    .nav-item:hover{background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.9);}
    .nav-item.active{background:linear-gradient(90deg,rgba(74,158,48,0.35),rgba(74,158,48,0.1));color:var(--white);border-left:3px solid var(--green-light);}
    .nav-icon{font-size:1.1rem;width:22px;text-align:center;}
    .sidebar-footer{margin-top:auto;padding:1rem 0.9rem;border-top:1px solid rgba(255,255,255,0.07);}
    .admin-profile{display:flex;align-items:center;gap:0.75rem;padding:0.7rem 0.9rem;border-radius:10px;text-decoration:none;transition:background 0.2s;}
    .admin-profile:hover{background:rgba(255,255,255,0.07);}
    .admin-av{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--green-main),var(--green-dark));display:grid;place-items:center;font-size:0.75rem;font-weight:700;color:var(--white);border:2px solid rgba(126,196,79,0.4);}
    .admin-info strong{display:block;font-size:0.83rem;color:var(--white);font-weight:600;}
    .admin-info span{font-size:0.72rem;color:rgba(255,255,255,0.45);}
    .main-area{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;}
    .topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;box-shadow:0 2px 12px rgba(45,106,31,0.06);}
    .page-title h1{font-family:"Playfair Display",serif;font-size:1.3rem;color:var(--green-dark);}
    .page-title span{font-size:0.78rem;color:var(--grey-light);}
    .content{padding:2rem;flex:1;}
    .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;}
    .stat-card{background:var(--white);border:1.5px solid var(--border);border-radius:18px;padding:1.5rem 1.6rem;display:flex;align-items:center;gap:1rem;transition:transform 0.2s,box-shadow 0.2s;}
    .stat-card:hover{transform:translateY(-4px);box-shadow:0 10px 28px rgba(45,106,31,0.1);}
    .stat-icon{width:52px;height:52px;border-radius:14px;display:grid;place-items:center;font-size:1.5rem;flex-shrink:0;}
    .si-green{background:var(--green-pale);}
    .si-blue{background:var(--blue-light);}
    .si-orange{background:#fde8d0;}
    .si-red{background:var(--red-light);}
    .stat-val{font-family:"Playfair Display",serif;font-size:2rem;color:var(--black);line-height:1;}
    .stat-label{font-size:0.78rem;color:var(--grey);margin-top:0.2rem;}
    .btn-back{padding:0.55rem 1.2rem;border:1.5px solid var(--border);border-radius:50px;background:var(--white);color:var(--grey);font-family:"DM Sans",sans-serif;font-size:0.85rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;}
    .btn-back:hover{background:var(--bg);}
  </style>
</head>
<body>

<aside class="sidebar">
  <a href="index.php?page=backoffice" class="sidebar-logo">
    <div class="logo-icon">🌿</div>
    <span class="logo-text">Eco<span>Nutri</span></span>
  </a>
  <div class="sidebar-section">
    <div class="sidebar-label">Navigation</div>
    <a href="index.php?page=backoffice" class="nav-item"><span class="nav-icon">📊</span> Tableau de bord</a>
    <a href="index.php?page=users" class="nav-item"><span class="nav-icon">👥</span> Utilisateurs</a>
    <a href="index.php?page=users&action=stats" class="nav-item active"><span class="nav-icon">📈</span> Statistiques</a>
  </div>
  <div class="sidebar-footer">
    <a href="index.php?page=logout" class="admin-profile">
      <div class="admin-av"><?= strtoupper(substr($_SESSION['user_nom'] ?? 'A', 0, 1)) ?></div>
      <div class="admin-info">
        <strong><?= htmlspecialchars($_SESSION['user_nom'] ?? '') ?></strong>
        <span>Administrateur</span>
      </div>
      <span style="margin-left:auto;color:rgba(255,255,255,0.3);">🚪</span>
    </a>
  </div>
</aside>

<div class="main-area">
  <div class="topbar">
    <div class="page-title">
      <h1>Statistiques Utilisateurs</h1>
      <span>Administration · Vue d'ensemble</span>
    </div>
    <a href="index.php?page=users" class="btn-back">← Retour à la liste</a>
  </div>

  <div class="content">

    <!-- Les 4 mêmes cartes statistiques -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon si-green">👥</div>
        <div>
          <div class="stat-val"><?= $stats['total'] ?></div>
          <div class="stat-label">Total utilisateurs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-blue">👤</div>
        <div>
          <div class="stat-val"><?= $stats['users'] ?></div>
          <div class="stat-label">Utilisateurs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-orange">⭐</div>
        <div>
          <div class="stat-val"><?= $stats['admins'] ?></div>
          <div class="stat-label">Administrateurs</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon si-green">🆕</div>
        <div>
          <div class="stat-val"><?= $stats['recent'] ?></div>
          <div class="stat-label">Inscrits cette semaine</div>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
