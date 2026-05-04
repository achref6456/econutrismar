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

    /* Stats */
    .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;}
    .stat-card{background:var(--white);border:1.5px solid var(--border);border-radius:14px;padding:1.1rem 1.3rem;display:flex;align-items:center;gap:0.9rem;}
    .stat-icon{width:44px;height:44px;border-radius:12px;display:grid;place-items:center;font-size:1.3rem;flex-shrink:0;}
    .si-green{background:var(--green-pale);}
    .si-blue{background:var(--blue-light);}
    .si-orange{background:#fde8d0;}
    .si-red{background:var(--red-light);}
    .stat-val{font-family:"Playfair Display",serif;font-size:1.5rem;color:var(--black);line-height:1;}
    .stat-label{font-size:0.75rem;color:var(--grey);margin-top:0.15rem;}

    /* Search & Tri */
    .toolbar{display:flex;align-items:center;gap:1rem;margin-bottom:1.2rem;flex-wrap:wrap;}
    .search-wrap{display:flex;align-items:center;background:var(--white);border:1.5px solid var(--border);border-radius:10px;padding:0.5rem 0.9rem;gap:0.5rem;flex:1;min-width:220px;}
    .search-wrap input{border:none;outline:none;background:transparent;font-family:"DM Sans",sans-serif;font-size:0.88rem;color:var(--black);width:100%;}
    .btn-search{padding:0.5rem 1.1rem;border:none;border-radius:8px;background:var(--green-main);color:var(--white);font-family:"DM Sans",sans-serif;font-size:0.85rem;font-weight:600;cursor:pointer;}
    .btn-reset{padding:0.5rem 1rem;border:1.5px solid var(--border);border-radius:8px;background:var(--white);color:var(--grey);font-family:"DM Sans",sans-serif;font-size:0.85rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
    .tri-wrap{display:flex;align-items:center;gap:0.5rem;}
    .tri-wrap label{font-size:0.82rem;color:var(--grey);white-space:nowrap;}
    .tri-select{padding:0.5rem 0.8rem;border:1.5px solid var(--border);border-radius:8px;font-family:"DM Sans",sans-serif;font-size:0.85rem;color:var(--black);background:var(--white);outline:none;cursor:pointer;}

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
    thead th a{color:var(--grey-light);text-decoration:none;display:flex;align-items:center;gap:0.3rem;}
    thead th a:hover{color:var(--green-main);}
    thead th a.active{color:var(--green-dark);}
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
    .badge-red{background:var(--red-light);color:var(--red);}
    .action-btns{display:flex;gap:0.4rem;}
    .action-btn{width:30px;height:30px;border-radius:8px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;display:grid;place-items:center;font-size:0.85rem;transition:all 0.2s;text-decoration:none;}
    .action-btn:hover{border-color:var(--green-main);background:var(--green-pale);}
    .action-btn.danger:hover{border-color:var(--red);background:var(--red-light);}
    .empty-state{text-align:center;padding:3rem;color:var(--grey);}
    .empty-state .es-icon{font-size:3rem;margin-bottom:0.8rem;}
    .search-info{font-size:0.82rem;color:var(--grey);padding:0.5rem 1rem;background:var(--bg);border-bottom:1px solid var(--border);}
    .sort-arrow{font-size:0.7rem;}
  </style>
</head>
<body>

<?php
// Paramètres actuels
$keyword = trim($_GET['search'] ?? '');
$tri     = $_GET['tri']         ?? 'id';
$ordre   = $_GET['ordre']       ?? 'ASC';

// Fonction pour générer le lien de tri
function triUrl(string $col, string $triActuel, string $ordreActuel, string $keyword): string {
    $nouvelOrdre = ($col === $triActuel && $ordreActuel === 'ASC') ? 'DESC' : 'ASC';
    $k = urlencode($keyword);
    return "index.php?page=users&tri={$col}&ordre={$nouvelOrdre}&search={$k}";
}

function triArrow(string $col, string $triActuel, string $ordreActuel): string {
    if ($col !== $triActuel) return '↕';
    return $ordreActuel === 'ASC' ? '↑' : '↓';
}
?>

<aside class="sidebar">
  <a href="index.php?page=backoffice" class="sidebar-logo">
    <div class="logo-icon">🌿</div>
    <span class="logo-text">Eco<span>Nutri</span></span>
  </a>
  <div class="sidebar-section">
    <div class="sidebar-label">Navigation</div>
    <a href="index.php?page=backoffice" class="nav-item"><span class="nav-icon">📊</span> Tableau de bord</a>
    <a href="index.php?page=users" class="nav-item active"><span class="nav-icon">👥</span> Utilisateurs</a>
    <a href="index.php?page=users&action=stats" class="nav-item"><span class="nav-icon">📈</span> Statistiques</a>
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
      <span>Administration · <?= count($users) ?> résultat(s)</span>
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

    <!-- Statistiques -->
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

    <!-- Barre de recherche et tri -->
    <form method="GET" action="index.php" class="toolbar">
      <input type="hidden" name="page" value="users" />
      <input type="hidden" name="tri" value="<?= htmlspecialchars($tri) ?>" />
      <input type="hidden" name="ordre" value="<?= htmlspecialchars($ordre) ?>" />

      <div class="search-wrap">
        <span>🔍</span>
        <input type="text" name="search"
               value="<?= htmlspecialchars($keyword) ?>"
               placeholder="Rechercher par nom, prénom ou email…" />
      </div>
      <button type="submit" class="btn-search">Rechercher</button>
      <?php if ($keyword !== ''): ?>
        <a href="index.php?page=users" class="btn-reset">✕ Effacer</a>
      <?php endif; ?>

      <div class="tri-wrap">
        <label>Trier par :</label>
        <select name="tri" class="tri-select" onchange="this.form.submit()">
          <option value="id"         <?= $tri === 'id'         ? 'selected' : '' ?>>Numéro</option>
          <option value="created_at" <?= $tri === 'created_at' ? 'selected' : '' ?>>Date d'inscription</option>
          <option value="nom"        <?= $tri === 'nom'        ? 'selected' : '' ?>>Nom</option>
          <option value="prenom"     <?= $tri === 'prenom'     ? 'selected' : '' ?>>Prénom</option>
          <option value="email"      <?= $tri === 'email'      ? 'selected' : '' ?>>Email</option>
          <option value="role"       <?= $tri === 'role'       ? 'selected' : '' ?>>Rôle</option>
        </select>
        <select name="ordre" class="tri-select" onchange="this.form.submit()">
          <option value="DESC" <?= $ordre === 'DESC' ? 'selected' : '' ?>>↓ Décroissant</option>
          <option value="ASC"  <?= $ordre === 'ASC'  ? 'selected' : '' ?>>↑ Croissant</option>
        </select>
      </div>
    </form>

    <div class="panel">
      <div class="panel-header">
        <h3>👥 Liste des utilisateurs</h3>
      </div>

      <?php if ($keyword !== ''): ?>
        <div class="search-info">
          🔍 Résultats pour "<strong><?= htmlspecialchars($keyword) ?></strong>" — <?= count($users) ?> trouvé(s)
        </div>
      <?php endif; ?>

      <div class="table-wrap">
        <?php if (empty($users)): ?>
          <div class="empty-state">
            <div class="es-icon">🔍</div>
            <p>Aucun utilisateur trouvé<?= $keyword !== '' ? ' pour "' . htmlspecialchars($keyword) . '"' : '' ?>.</p>
          </div>
        <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th><a href="<?= triUrl('nom', $tri, $ordre, $keyword) ?>" class="<?= $tri === 'nom' ? 'active' : '' ?>">Utilisateur <span class="sort-arrow"><?= triArrow('nom', $tri, $ordre) ?></span></a></th>
              <th><a href="<?= triUrl('email', $tri, $ordre, $keyword) ?>" class="<?= $tri === 'email' ? 'active' : '' ?>">Email <span class="sort-arrow"><?= triArrow('email', $tri, $ordre) ?></span></a></th>
              <th><a href="<?= triUrl('role', $tri, $ordre, $keyword) ?>" class="<?= $tri === 'role' ? 'active' : '' ?>">Rôle <span class="sort-arrow"><?= triArrow('role', $tri, $ordre) ?></span></a></th>
              <th><a href="<?= triUrl('created_at', $tri, $ordre, $keyword) ?>" class="<?= $tri === 'created_at' ? 'active' : '' ?>">Inscrit le <span class="sort-arrow"><?= triArrow('created_at', $tri, $ordre) ?></span></a></th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $total_users = count($users);
            $i = ($ordre === 'ASC') ? 1 : $total_users;
            foreach ($users as $u): ?>
            <tr>
              <td><?= $i; $ordre === 'ASC' ? $i++ : $i--; ?></td>
              <td>
                <div class="td-user">
                  <?php if (!empty($u['avatar'])): ?>
                    <img src="uploads/avatars/<?= htmlspecialchars($u['avatar']) ?>"
                         style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid var(--green-main);flex-shrink:0;" />
                  <?php else: ?>
                    <div class="td-av"><?= strtoupper(substr($u['prenom'], 0, 1)) ?></div>
                  <?php endif; ?>
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
              <td>
                <?php
                  $banned = !empty($u['banned_until']) && strtotime($u['banned_until']) > time();
                ?>
                <?php if ($banned): ?>
                  <span class="badge badge-red">🚫 Banni jusqu'au <?= date('d/m/Y', strtotime($u['banned_until'])) ?></span>
                <?php else: ?>
                  <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                <?php endif; ?>
              </td>
              <td>
                <div class="action-btns">
                  <a href="index.php?page=users&action=edit&id=<?= $u['id'] ?>" class="action-btn" title="Modifier">✏️</a>
                  <?php if ($banned): ?>
                    <a href="index.php?page=users&action=unban&id=<?= $u['id'] ?>"
                       class="action-btn" title="Débannir"
                       onclick="return confirm('Débannir cet utilisateur ?')">✅</a>
                  <?php else: ?>
                    <a href="index.php?page=users&action=ban&id=<?= $u['id'] ?>"
                       class="action-btn danger" title="Bannir 3 jours"
                       onclick="return confirm('Bannir <?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?> pour 3 jours ?')">🚫</a>
                  <?php endif; ?>
                  <a href="index.php?page=users&action=delete&id=<?= $u['id'] ?>"
                     class="action-btn danger" title="Supprimer"
                     onclick="return confirm('Supprimer <?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?> ?')">🗑️</a>
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
