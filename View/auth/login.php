<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --green-dark:#2d6a1f; --green-main:#4a9e30; --green-light:#7ec44f;
      --green-pale:#e8f5e1; --orange:#f07c1b; --black:#111; --grey:#666;
      --white:#fff; --border:#e4eed9; --bg:#f2f8ee;
      --red:#e53935; --red-light:#fdecea;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: "DM Sans", sans-serif;
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 2rem;
      background: linear-gradient(160deg, var(--green-pale) 0%, #d4edbc 55%, #f5fff0 100%);
    }

    .card {
      background: var(--white);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(45,106,31,0.15);
      width: min(460px, 100%);
      overflow: hidden;
    }

    /* ── En-tête ── */
    .card-head {
      background: linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding: 2rem 2.2rem 1.8rem;
      text-align: center;
    }
    .logo {
      display: inline-flex; align-items: center; gap: 0.6rem;
      text-decoration: none; margin-bottom: 1.2rem;
    }
    .logo-icon {
      width: 44px; height: 44px;
      background: rgba(255,255,255,0.15);
      border-radius: 12px;
      display: grid; place-items: center;
      border: 1px solid rgba(255,255,255,0.3);
      font-size: 1.4rem;
    }
    .logo-text { font-family:"Playfair Display",serif; font-size:1.5rem; color:var(--white); }
    .logo-text span { color: var(--orange); }
    .card-head h1 { font-family:"Playfair Display",serif; font-size:1.3rem; color:var(--white); margin-bottom:0.3rem; }
    .card-head p  { font-size:0.83rem; color:rgba(255,255,255,0.72); }

    /* ── Corps ── */
    .card-body { padding: 2rem 2.2rem; }

    .alert {
      padding: 0.8rem 1rem; border-radius: 10px;
      font-size: 0.85rem; margin-bottom: 1.2rem;
      display: flex; align-items: center; gap: 0.5rem;
    }
    .alert-error   { background:var(--red-light);   color:var(--red);        border:1px solid #f5c6c6; }
    .alert-success { background:var(--green-pale);  color:var(--green-dark); border:1px solid #c3e6a8; }

    .fg { margin-bottom: 1.1rem; }
    .fg label {
      display: block; font-size:0.82rem; font-weight:600;
      color:var(--green-dark); margin-bottom:0.38rem;
    }
    .fg label em { color:var(--orange); font-style:normal; }

    .input-wrap { position: relative; }
    .input-icon {
      position:absolute; left:0.9rem; top:50%;
      transform:translateY(-50%); font-size:1rem; pointer-events:none;
    }
    .fc {
      width:100%; padding:0.7rem 2.8rem 0.7rem 2.6rem;
      border:1.5px solid var(--border); border-radius:10px;
      font-family:"DM Sans",sans-serif; font-size:0.88rem;
      color:var(--black); outline:none;
      transition:border-color 0.2s, background 0.2s;
      background:var(--bg);
    }
    .fc:focus    { border-color:var(--green-main); background:var(--white); }
    .fc.is-error { border-color:var(--red);        background:var(--red-light); }

    .pwd-toggle {
      position:absolute; right:0.9rem; top:50%;
      transform:translateY(-50%);
      background:none; border:none; cursor:pointer;
      font-size:1rem; color:var(--grey); padding:0;
    }

    .field-error {
      font-size:0.76rem; color:var(--red);
      margin-top:0.3rem; display:flex; align-items:center; gap:0.3rem;
    }

    /* Remember me */
    .remember-row {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 1.1rem;
    }
    .remember-label {
      display: flex; align-items: center; gap: 0.5rem;
      font-size: 0.83rem; color: var(--grey); cursor: pointer;
    }
    .remember-label input[type="checkbox"] {
      width: 16px; height: 16px; accent-color: var(--green-main); cursor: pointer;
    }

    .btn-submit {
      width:100%; padding:0.8rem; border:none; border-radius:50px;
      background:linear-gradient(135deg, var(--green-main), var(--green-dark));
      color:var(--white); font-family:"DM Sans",sans-serif;
      font-size:0.95rem; font-weight:700; cursor:pointer;
      transition:transform 0.2s, box-shadow 0.2s; margin-top:0.5rem;
    }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(45,106,31,0.3); }

    /* ── Pied ── */
    .card-foot {
      text-align:center; padding:1.2rem 2.2rem 1.8rem;
      border-top:1px solid var(--border);
      font-size:0.85rem; color:var(--grey);
    }
    .card-foot a { color:var(--green-main); font-weight:700; text-decoration:none; }
    .card-foot a:hover { color:var(--green-dark); }

    /* Hint compte test */
    .test-hint {
      background: #fffbea; border:1px solid #ffe082;
      border-radius:10px; padding:0.7rem 1rem;
      font-size:0.78rem; color:#7a5c00;
      margin-bottom:1.2rem; line-height:1.6;
    }
    .test-hint strong { color:#5a3e00; }
  </style>
</head>
<body>

<div class="card">

  <div class="card-head">
    <a href="index.php" class="logo">
      <div class="logo-icon">🌿</div>
      <span class="logo-text">Eco<span>Nutri</span></span>
    </a>
    <h1>Bon retour !</h1>
    <p>Connectez-vous à votre espace personnel</p>
  </div>

  <div class="card-body">

    <!-- Compte de test -->
    <div class="test-hint">
      🧪 <strong>Compte test admin :</strong><br>
      Email : <strong>admin@econutri.com</strong><br>
      Mot de passe : <strong>Admin1234</strong>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($errors['global'])): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($errors['global']) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login" novalidate>

      <div class="fg">
        <label for="email">Adresse email <em>*</em></label>
        <div class="input-wrap">
          <span class="input-icon">📧</span>
          <input
            type="text"
            id="email"
            name="email"
            class="fc <?= !empty($errors['email']) ? 'is-error' : '' ?>"
            value="<?= htmlspecialchars($_POST['email'] ?? ($_COOKIE['remember_email'] ?? '')) ?>"
            placeholder="votre@email.com"
            autocomplete="email"
          />
        </div>
        <?php if (!empty($errors['email'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="fg">
        <label for="password">Mot de passe <em>*</em></label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input
            type="password"
            id="password"
            name="password"
            class="fc <?= !empty($errors['password']) ? 'is-error' : '' ?>"
            placeholder="••••••••"
            autocomplete="current-password"
          />
          <button type="button" class="pwd-toggle" onclick="togglePwd('password', this)">👁</button>
        </div>
        <?php if (!empty($errors['password'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['password']) ?></div>
        <?php endif; ?>
      </div>

      <div class="remember-row">
        <label class="remember-label">
          <input type="checkbox" name="remember" value="1" <?= !empty($_POST['remember']) ? 'checked' : '' ?> />
          Se souvenir de moi
        </label>
        <a href="index.php?page=forgot-password" class="forgot">Mot de passe oublié ?</a>
      </div>

      <button type="submit" class="btn-submit">Se connecter →</button>
    </form>
  </div>

  <div class="card-foot">
    Pas encore de compte ?
    <a href="index.php?page=register">Créer un compte</a>
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
