<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Créer un compte</title>
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
      font-family:"DM Sans",sans-serif;
      min-height:100vh; display:grid; place-items:center; padding:2rem;
      background:linear-gradient(160deg, var(--green-pale) 0%, #d4edbc 55%, #f5fff0 100%);
    }

    .card {
      background:var(--white); border-radius:24px;
      box-shadow:0 20px 60px rgba(45,106,31,0.15);
      width:min(520px, 100%); overflow:hidden;
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

    .fg { margin-bottom:1rem; }
    .fg label { display:block; font-size:0.82rem; font-weight:600; color:var(--green-dark); margin-bottom:0.38rem; }
    .fg label em { color:var(--orange); font-style:normal; }
    .fg-row { display:grid; grid-template-columns:1fr 1fr; gap:0.9rem; }

    .input-wrap { position:relative; }
    .input-icon { position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; }
    .fc {
      width:100%; padding:0.7rem 2.8rem 0.7rem 2.6rem;
      border:1.5px solid var(--border); border-radius:10px;
      font-family:"DM Sans",sans-serif; font-size:0.88rem;
      color:var(--black); outline:none;
      transition:border-color 0.2s, background 0.2s; background:var(--bg);
    }
    .fc:focus    { border-color:var(--green-main); background:var(--white); }
    .fc.is-error { border-color:var(--red);        background:var(--red-light); }

    .pwd-toggle { position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:1rem; color:var(--grey); padding:0; }

    .field-error { font-size:0.76rem; color:var(--red); margin-top:0.3rem; display:flex; align-items:center; gap:0.3rem; }

    /* Barre de force du mot de passe */
    .pwd-strength { margin-top:0.5rem; }
    .pwd-bar { height:4px; border-radius:2px; background:var(--border); overflow:hidden; margin-bottom:0.25rem; }
    .pwd-fill { height:100%; border-radius:2px; transition:width 0.3s, background 0.3s; width:0; }
    .pwd-label { font-size:0.74rem; color:var(--grey); }

    .btn-submit {
      width:100%; padding:0.8rem; border:none; border-radius:50px;
      background:linear-gradient(135deg, var(--green-main), var(--green-dark));
      color:var(--white); font-family:"DM Sans",sans-serif;
      font-size:0.95rem; font-weight:700; cursor:pointer;
      transition:transform 0.2s, box-shadow 0.2s; margin-top:0.8rem;
    }
    .btn-submit:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(45,106,31,0.3); }

    .terms { font-size:0.76rem; color:var(--grey); text-align:center; margin-top:0.8rem; line-height:1.5; }
    .terms a { color:var(--green-main); }

    .card-foot { text-align:center; padding:1.2rem 2.2rem 1.8rem; border-top:1px solid var(--border); font-size:0.85rem; color:var(--grey); }
    .card-foot a { color:var(--green-main); font-weight:700; text-decoration:none; }
    .card-foot a:hover { color:var(--green-dark); }

    /* Règles mot de passe */
    .pwd-rules { margin-top:0.5rem; display:flex; flex-direction:column; gap:0.2rem; }
    .rule { font-size:0.74rem; display:flex; align-items:center; gap:0.4rem; color:var(--grey); }
    .rule.ok  { color:var(--green-main); }
    .rule.bad { color:var(--red); }
    .rule-dot { width:8px; height:8px; border-radius:50%; background:currentColor; flex-shrink:0; }
  </style>
</head>
<body>

<div class="card">

  <div class="card-head">
    <a href="index.php" class="logo">
      <div class="logo-icon">🌿</div>
      <span class="logo-text">Eco<span>Nutri</span></span>
    </a>
    <h1>Créer un compte</h1>
    <p>Rejoignez la communauté EcoNutri</p>
  </div>

  <div class="card-body">
    <form method="POST" action="index.php?page=register" novalidate>

      <div class="fg-row">
        <div class="fg">
          <label for="nom">Nom <em>*</em></label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input type="text" id="nom" name="nom"
              class="fc <?= !empty($errors['nom']) ? 'is-error' : '' ?>"
              value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
              placeholder="Dupont" />
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
              value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
              placeholder="Marie" />
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
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            placeholder="votre@email.com"
            autocomplete="email" />
        </div>
        <?php if (!empty($errors['email'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['email']) ?></div>
        <?php endif; ?>
      </div>

      <div class="fg">
        <label for="password">Mot de passe <em>*</em></label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input type="password" id="password" name="password"
            class="fc <?= !empty($errors['password']) ? 'is-error' : '' ?>"
            placeholder="Min. 8 car., 1 majuscule, 1 chiffre"
            autocomplete="new-password"
            oninput="checkStrength(this.value)" />
          <button type="button" class="pwd-toggle" onclick="togglePwd('password',this)">👁</button>
        </div>
        <?php if (!empty($errors['password'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['password']) ?></div>
        <?php endif; ?>
        <div class="pwd-strength">
          <div class="pwd-bar"><div class="pwd-fill" id="pwdFill"></div></div>
          <div class="pwd-label" id="pwdLabel">Entrez un mot de passe</div>
        </div>
        <div class="pwd-rules">
          <div class="rule" id="r-len"><span class="rule-dot"></span>Au moins 8 caractères</div>
          <div class="rule" id="r-maj"><span class="rule-dot"></span>Au moins une majuscule</div>
          <div class="rule" id="r-num"><span class="rule-dot"></span>Au moins un chiffre</div>
        </div>
      </div>

      <div class="fg">
        <label for="confirm_password">Confirmer le mot de passe <em>*</em></label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input type="password" id="confirm_password" name="confirm_password"
            class="fc <?= !empty($errors['confirm_password']) ? 'is-error' : '' ?>"
            placeholder="Répétez le mot de passe"
            autocomplete="new-password" />
          <button type="button" class="pwd-toggle" onclick="togglePwd('confirm_password',this)">👁</button>
        </div>
        <?php if (!empty($errors['confirm_password'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['confirm_password']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-submit">Créer mon compte →</button>

      <p class="terms">
        En créant un compte, vous acceptez nos
        <a href="#">Conditions d'utilisation</a>.
      </p>
    </form>
  </div>

  <div class="card-foot">
    Déjà un compte ?
    <a href="index.php?page=login">Se connecter</a>
  </div>

</div>

<script>
function togglePwd(id, btn) {
  const f = document.getElementById(id);
  f.type  = f.type === 'password' ? 'text' : 'password';
  btn.textContent = f.type === 'password' ? '👁' : '🙈';
}

function checkStrength(pwd) {
  const fill  = document.getElementById('pwdFill');
  const label = document.getElementById('pwdLabel');
  const rLen  = document.getElementById('r-len');
  const rMaj  = document.getElementById('r-maj');
  const rNum  = document.getElementById('r-num');

  const okLen = pwd.length >= 8;
  const okMaj = /[A-Z]/.test(pwd);
  const okNum = /[0-9]/.test(pwd);

  rLen.className = 'rule ' + (okLen ? 'ok' : (pwd.length > 0 ? 'bad' : ''));
  rMaj.className = 'rule ' + (okMaj ? 'ok' : (pwd.length > 0 ? 'bad' : ''));
  rNum.className = 'rule ' + (okNum ? 'ok' : (pwd.length > 0 ? 'bad' : ''));

  const score = [okLen, okMaj, okNum].filter(Boolean).length;
  const levels = [
    { pct:'0%',   color:'#ccc',             text:'Entrez un mot de passe' },
    { pct:'33%',  color:'var(--red)',        text:'⚠ Faible' },
    { pct:'66%',  color:'#f07c1b',           text:'⚡ Moyen' },
    { pct:'100%', color:'var(--green-main)', text:'✅ Fort' },
  ];
  const lvl = pwd.length === 0 ? levels[0] : levels[score];
  fill.style.width      = lvl.pct;
  fill.style.background = lvl.color;
  label.textContent     = lvl.text;
  label.style.color     = lvl.color;
}
</script>
</body>
</html>
