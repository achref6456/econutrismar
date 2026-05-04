<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Mot de passe oublié</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --green-dark:#2d6a1f; --green-main:#4a9e30; --green-light:#7ec44f;
      --green-pale:#e8f5e1; --orange:#f07c1b; --black:#111; --grey:#666;
      --white:#fff; --border:#e4eed9; --bg:#f2f8ee;
      --red:#e53935; --red-light:#fdecea;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body {
      font-family:"DM Sans",sans-serif; min-height:100vh;
      display:grid; place-items:center; padding:2rem;
      background:linear-gradient(160deg, var(--green-pale) 0%, #d4edbc 55%, #f5fff0 100%);
    }
    .card {
      background:var(--white); border-radius:24px;
      box-shadow:0 20px 60px rgba(45,106,31,0.15);
      width:min(460px, 100%); overflow:hidden;
    }
    .card-head {
      background:linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding:2rem 2.2rem 1.8rem; text-align:center;
    }
    .logo { display:inline-flex; align-items:center; gap:0.6rem; text-decoration:none; margin-bottom:1.2rem; }
    .logo-icon { width:44px; height:44px; background:rgba(255,255,255,0.15); border-radius:12px; display:grid; place-items:center; border:1px solid rgba(255,255,255,0.3); font-size:1.4rem; }
    .logo-text { font-family:"Playfair Display",serif; font-size:1.5rem; color:var(--white); }
    .logo-text span { color:var(--orange); }
    .card-head h1 { font-family:"Playfair Display",serif; font-size:1.3rem; color:var(--white); margin-bottom:0.3rem; }
    .card-head p  { font-size:0.83rem; color:rgba(255,255,255,0.72); }
    .card-body { padding:2rem 2.2rem; }
    .alert { padding:0.8rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:0.5rem; }
    .alert-error   { background:var(--red-light);  color:var(--red);        border:1px solid #f5c6c6; }
    .alert-success { background:var(--green-pale); color:var(--green-dark); border:1px solid #c3e6a8; flex-direction:column; align-items:flex-start; gap:0.5rem; }
    .new-pwd {
      background:var(--green-dark); color:var(--white);
      font-size:1.3rem; font-weight:700; letter-spacing:2px;
      padding:0.6rem 1.2rem; border-radius:10px;
      font-family:monospace; margin-top:0.3rem;
    }
    .fg { margin-bottom:1.1rem; }
    .fg label { display:block; font-size:0.82rem; font-weight:600; color:var(--green-dark); margin-bottom:0.38rem; }
    .input-wrap { position:relative; }
    .input-icon { position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; }
    .fc { width:100%; padding:0.7rem 0.9rem 0.7rem 2.6rem; border:1.5px solid var(--border); border-radius:10px; font-family:"DM Sans",sans-serif; font-size:0.88rem; color:var(--black); outline:none; transition:border-color 0.2s; background:var(--bg); }
    .fc:focus { border-color:var(--green-main); background:var(--white); }
    .fc.is-error { border-color:var(--red); background:var(--red-light); }
    .field-error { font-size:0.76rem; color:var(--red); margin-top:0.3rem; display:flex; align-items:center; gap:0.3rem; }
    .btn-submit { width:100%; padding:0.8rem; border:none; border-radius:50px; background:linear-gradient(135deg,var(--green-main),var(--green-dark)); color:var(--white); font-family:"DM Sans",sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer; transition:transform 0.2s; margin-top:0.5rem; }
    .btn-submit:hover { transform:translateY(-2px); }
    .card-foot { text-align:center; padding:1.2rem 2.2rem 1.8rem; border-top:1px solid var(--border); font-size:0.85rem; color:var(--grey); }
    .card-foot a { color:var(--green-main); font-weight:700; text-decoration:none; }
  </style>
</head>
<body>

<div class="card">
  <div class="card-head">
    <a href="index.php" class="logo">
      <div class="logo-icon">🌿</div>
      <span class="logo-text">Eco<span>Nutri</span></span>
    </a>
    <h1>Mot de passe oublié ?</h1>
    <p>Entrez votre email pour réinitialiser votre mot de passe</p>
  </div>

  <div class="card-body">

    <?php if (!empty($success)): ?>
      <div class="alert alert-success">
        ✅ <?= htmlspecialchars($success) ?>
        <div class="new-pwd"><?= htmlspecialchars($newPwd) ?></div>
        <div style="font-size:0.78rem;color:var(--green-dark);margin-top:0.3rem;">
          ⚠️ Notez ce mot de passe et changez-le après connexion.
        </div>
        <a href="index.php?page=login" style="color:var(--green-main);font-weight:700;font-size:0.85rem;">→ Se connecter maintenant</a>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors['email'])): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($errors['email']) ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST" action="index.php?page=forgot-password" novalidate>
      <div class="fg">
        <label for="email">Adresse email <em style="color:var(--orange);">*</em></label>
        <div class="input-wrap">
          <span class="input-icon">📧</span>
          <input type="text" id="email" name="email"
            class="fc <?= !empty($errors['email']) ? 'is-error' : '' ?>"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            placeholder="votre@email.com" />
        </div>
      </div>
      <button type="submit" class="btn-submit">🔑 Réinitialiser le mot de passe</button>
    </form>
    <?php endif; ?>

  </div>

  <div class="card-foot">
    <a href="index.php?page=login">← Retour à la connexion</a>
  </div>
</div>

</body>
</html>
