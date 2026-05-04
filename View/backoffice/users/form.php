<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – <?= isset($user) ? 'Modifier' : 'Ajouter' ?> un utilisateur</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root{--green-dark:#2d6a1f;--green-main:#4a9e30;--green-light:#7ec44f;--green-pale:#e8f5e1;--orange:#f07c1b;--black:#111;--grey:#666;--grey-light:#999;--white:#fff;--border:#e4eed9;--sidebar-bg:#0e2a08;--sidebar-w:260px;--topbar-h:68px;--bg:#f2f8ee;--red:#e53935;--red-light:#fdecea;}
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
    .topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);padding:0 2rem;display:flex;align-items:center;position:sticky;top:0;z-index:40;box-shadow:0 2px 12px rgba(45,106,31,0.06);}
    .page-title h1{font-family:"Playfair Display",serif;font-size:1.3rem;color:var(--green-dark);}
    .page-title span{font-size:0.78rem;color:var(--grey-light);}
    .content{padding:2rem;flex:1;}
    .form-card{background:var(--white);border:1.5px solid var(--border);border-radius:18px;overflow:hidden;max-width:600px;}
    .form-head{background:linear-gradient(135deg,var(--green-dark),var(--green-main));padding:1.4rem 1.8rem;}
    .form-head h2{font-family:"Playfair Display",serif;font-size:1.15rem;color:var(--white);}
    .form-head p{font-size:0.78rem;color:rgba(255,255,255,0.7);margin-top:0.15rem;}
    .form-body{padding:1.8rem;}
    .fg{margin-bottom:1.1rem;}
    .fg label{display:block;font-size:0.82rem;font-weight:600;color:var(--green-dark);margin-bottom:0.38rem;}
    .fg label em{color:var(--orange);font-style:normal;}
    .fg-row{display:grid;grid-template-columns:1fr 1fr;gap:0.9rem;}
    .input-wrap{position:relative;}
    .input-icon{position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);font-size:1rem;pointer-events:none;}
    .fc{width:100%;padding:0.7rem 0.9rem 0.7rem 2.6rem;border:1.5px solid var(--border);border-radius:10px;font-family:"DM Sans",sans-serif;font-size:0.88rem;color:var(--black);outline:none;transition:border-color 0.2s,background 0.2s;background:var(--bg);}
    .fc:focus{border-color:var(--green-main);background:var(--white);}
    .fc.is-error{border-color:var(--red);background:var(--red-light);}
    .fc-select{padding-left:0.9rem;}
    .field-error{font-size:0.76rem;color:var(--red);margin-top:0.3rem;display:flex;align-items:center;gap:0.3rem;}
    .pwd-toggle{position:absolute;right:0.9rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1rem;color:var(--grey);padding:0;}
    .form-foot{padding:1rem 1.8rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.7rem;}
    .btn-cancel{padding:0.6rem 1.3rem;border:1.5px solid var(--border);border-radius:50px;background:transparent;font-family:"DM Sans",sans-serif;font-size:0.87rem;color:var(--grey);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;}
    .btn-save{padding:0.6rem 1.6rem;border:none;border-radius:50px;background:linear-gradient(135deg,var(--green-main),var(--green-dark));color:var(--white);font-family:"DM Sans",sans-serif;font-size:0.87rem;font-weight:700;cursor:pointer;transition:transform 0.2s,box-shadow 0.2s;display:inline-flex;align-items:center;gap:0.4rem;}
    .btn-save:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(45,106,31,0.3);}
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
      <h1><?= isset($user) ? 'Modifier' : 'Ajouter' ?> un utilisateur</h1>
      <span>Administration · Utilisateurs</span>
    </div>
  </div>

  <div class="content">
    <div class="form-card">
      <div class="form-head">
        <h2><?= isset($user) ? '✏️ Modifier l\'utilisateur' : '➕ Nouvel utilisateur' ?></h2>
        <p><?= isset($user) ? 'Modifiez les informations ci-dessous' : 'Remplissez le formulaire pour créer un compte' ?></p>
      </div>

      <?php
        $action = isset($user)
          ? 'index.php?page=users&action=update'
          : 'index.php?page=users&action=store';
        $fNom    = htmlspecialchars($_POST['nom']    ?? ($user['nom']    ?? ''));
        $fPrenom = htmlspecialchars($_POST['prenom'] ?? ($user['prenom'] ?? ''));
        $fEmail  = htmlspecialchars($_POST['email']  ?? ($user['email']  ?? ''));
        $fRole   = $_POST['role'] ?? ($user['role'] ?? 'user');
      ?>

      <form method="POST" action="<?= $action ?>" novalidate enctype="multipart/form-data">
        <?php if (isset($user)): ?>
          <input type="hidden" name="id" value="<?= $user['id'] ?>" />
        <?php endif; ?>

        <div class="form-body">
          <div class="fg-row">
            <div class="fg">
              <label for="nom">Nom <em>*</em></label>
              <div class="input-wrap">
                <span class="input-icon">👤</span>
                <input type="text" id="nom" name="nom"
                  class="fc <?= !empty($errors['nom']) ? 'is-error' : '' ?>"
                  value="<?= $fNom ?>" placeholder="Dupont" />
              </div>
              <?php if (!empty($errors['nom'])): ?>
                <div class="field-error">⚠ <?= htmlspecialchars($errors['nom']) ?></div>
              <?php endif; ?>
            </div>

            <div class="fg">
              <label for="prenom">Prénom <em>*</em></label>
              <div class="input-wrap">
                <span class="input-icon">👤</span>
                <input type="text" id="prenom" name="prenom"
                  class="fc <?= !empty($errors['prenom']) ? 'is-error' : '' ?>"
                  value="<?= $fPrenom ?>" placeholder="Marie" />
              </div>
              <?php if (!empty($errors['prenom'])): ?>
                <div class="field-error">⚠ <?= htmlspecialchars($errors['prenom']) ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="fg">
            <label for="email">Adresse email <em>*</em></label>
            <div class="input-wrap">
              <span class="input-icon">📧</span>
              <input type="text" id="email" name="email"
                class="fc <?= !empty($errors['email']) ? 'is-error' : '' ?>"
                value="<?= $fEmail ?>" placeholder="votre@email.com" />
            </div>
            <?php if (!empty($errors['email'])): ?>
              <div class="field-error">⚠ <?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
          </div>

          <?php if (!isset($user)): ?>
          <div class="fg">
            <label for="password">Mot de passe <em>*</em></label>
            <div class="input-wrap">
              <span class="input-icon">🔒</span>
              <input type="password" id="password" name="password"
                class="fc <?= !empty($errors['password']) ? 'is-error' : '' ?>"
                placeholder="Min. 8 caractères" />
              <button type="button" class="pwd-toggle" onclick="togglePwd('password',this)">👁</button>
            </div>
            <?php if (!empty($errors['password'])): ?>
              <div class="field-error">⚠ <?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
          </div>
          <?php endif; ?>

          <div class="fg">
            <label for="role">Rôle <em>*</em></label>
            <div class="input-wrap">
              <select id="role" name="role"
                class="fc fc-select <?= !empty($errors['role']) ? 'is-error' : '' ?>">
                <option value="user"  <?= $fRole === 'user'  ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $fRole === 'admin' ? 'selected' : '' ?>>Administrateur</option>
              </select>
            </div>
            <?php if (!empty($errors['role'])): ?>
              <div class="field-error">⚠ <?= htmlspecialchars($errors['role']) ?></div>
            <?php endif; ?>
          </div>

          <?php if (isset($user)): ?>
          <div class="fg">
            <label for="avatar">Photo de profil</label>
            <?php if (!empty($user['avatar'])): ?>
              <div style="margin-bottom:0.6rem;display:flex;align-items:center;gap:1rem;">
                <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>"
                     style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--green-main);" />
                <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.83rem;color:var(--red);cursor:pointer;">
                  <input type="checkbox" name="remove_avatar" value="1" />
                  Supprimer la photo
                </label>
              </div>
            <?php endif; ?>
            <div class="input-wrap">
              <input type="file" id="avatar" name="avatar" accept="image/*"
                class="fc <?= !empty($errors['avatar']) ? 'is-error' : '' ?>"
                style="padding:0.5rem;" />
            </div>
            <?php if (!empty($errors['avatar'])): ?>
              <div class="field-error">⚠ <?= htmlspecialchars($errors['avatar']) ?></div>
            <?php endif; ?>
            <div style="font-size:0.75rem;color:var(--grey);margin-top:0.3rem;">JPG, PNG, GIF, WEBP — max 2 Mo</div>
          </div>
          <?php endif; ?>
        </div>

        <div class="form-foot">
          <a href="index.php?page=users" class="btn-cancel">Annuler</a>
          <button type="submit" class="btn-save">
            <?= isset($user) ? '💾 Enregistrer' : '➕ Créer' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function togglePwd(id, btn) {
  const f = document.getElementById(id);
  f.type  = f.type === 'password' ? 'text' : 'password';
  btn.textContent = f.type === 'password' ? '👁' : '🙈';
}
</script>
</body>
</html>
