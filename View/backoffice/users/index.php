<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Utilisateurs</title>
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
    .alert{padding:0.8rem 1.2rem;border-radius:12px;font-size:0.85rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.6rem;}
    .alert-success{background:var(--green-pale);color:var(--green-dark);border:1px solid #c3e6a8;}
    .alert-error{background:var(--red-light);color:var(--red);border:1px solid #f5c6c6;}
    .panel{background:var(--white);border:1.5px solid var(--border);border-radius:18px;overflow:hidden;}
    .panel-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
    .panel-header h3{font-family:"Playfair Display",serif;font-size:1rem;color:var(--black);}
    .btn-add{padding:0.55rem 1.2rem;border:none;border-radius:50px;background:linear-gradient(135deg,var(--green-main),var(--green-dark));color:var(--white);font-family:"DM Sans",sans-serif;font-size:0.85rem;font-weight:700;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:0.4rem;transition:transform 0.2s,box-shadow 0.2s;}
    .btn-add:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(45,106,31,0.3);}
    .table-wrap{overflow-x:auto;}
    table{width:100%;border-collapse:collapse;font-size:0.83rem;}
    thead tr{border-bottom:2px solid var(--border);}
    thead th{text-align:left;padding:0.7rem 1rem;font-size:0.72rem;font-weight:700;color:var(--grey-light);text-transform:uppercase;letter-spacing:0.5px;white-space:nowrap;}
    tbody tr{border-bottom:1px solid var(--border);transition:background 0.15s;}
    tbody tr:last-child{border-bottom:none;}
    tbody tr:hover{background:var(--bg);}
    tbody td{padding:0.85rem 1rem;vertical-align:middle;}
    .td-user{display:flex;align-items:center;gap:0.65rem;}
    .td-av{width:34px;height:34px;border-radius:50%;display:grid;place-items:center;font-size:0.72rem;font-weight:700;color:var(--white);background:var(--green-main);}
    .td-name strong{display:block;font-size:0.83rem;}
    .td-name span{font-size:0.72rem;color:var(--grey-light);}
    .badge{display:inline-flex;align-items:center;padding:0.25rem 0.65rem;border-radius:50px;font-size:0.72rem;font-weight:700;}
    .badge-green{background:var(--green-pale);color:var(--green-dark);}
    .badge-blue{background:var(--blue-light);color:var(--blue);}
    .action-btns{display:flex;gap:0.4rem;}
    .action-btn{width:30px;height:30px;border-radius:8px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;display:grid;place-items:center;font-size:0.85rem;transition:all 0.2s;text-decoration:none;}
    .action-btn:hover{border-color:var(--green-main);background:var(--green-pale);}
    .action-btn.danger:hover{border-color:var(--red);background:var(--red-light);}
    .empty-state{text-align:center;padding:3rem;color:var(--grey);}
    .empty-state .es-icon{font-size:3rem;margin-bottom:0.8rem;}
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
    <a href="index.php?page=users" class="nav-item active"><span class="nav-icon">👥</span> Utilisateurs</a>
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
      <h1>Gestion des utilisateurs</h1>
      <span>Administration · <?= count($users) ?> utilisateur(s)</span>
    </div>
    <a href="index.php?page=users&action=create" class="btn-add">+ Ajouter</a>
  </div>

  <div class="content">

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION['error']) ?></div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="panel">
      <div class="panel-header">
        <h3>👥 Liste des utilisateurs</h3>
      </div>
      <div class="table-wrap">
        <?php if (empty($users)): ?>
          <div class="empty-state">
            <div class="es-icon">👤</div>
            <p>Aucun utilisateur trouvé.</p>
          </div>
        <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Utilisateur</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Inscrit le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td>
                <div class="td-user">
                  <div class="td-av"><?= strtoupper(substr($u['prenom'], 0, 1)) ?></div>
                  <div class="td-name">
                    <strong><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></strong>
                  </div>
                </div>
              </td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td>
                <?php if ($u['role'] === 'admin'): ?>
                  <span class="badge badge-green">Admin</span>
                <?php else: ?>
                  <span class="badge badge-blue">Utilisateur</span>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
              <td>
                <div class="action-btns">
                  <a href="index.php?page=users&action=edit&id=<?= $u['id'] ?>" class="action-btn" title="Modifier">✏️</a>
                  <a href="index.php?page=users&action=delete&id=<?= $u['id'] ?>"
                     class="action-btn danger" title="Supprimer"
                     onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

</body>
</html>
