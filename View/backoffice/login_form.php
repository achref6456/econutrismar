<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root { --green-dark:#2d6a1f; --green-main:#4a9e30; --orange:#f07c1b; --border:#e4eed9; --bg:#f2f8ee; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { min-height:100vh; display:grid; place-items:center; background:var(--bg); font-family:"DM Sans",sans-serif; padding:1.5rem; }
    .card { background:#fff; border:1.5px solid var(--border); border-radius:20px; width:min(420px,100%); overflow:hidden; box-shadow:0 12px 40px rgba(45,106,31,.1); }
    .head { background:linear-gradient(135deg,var(--green-dark),var(--green-main)); color:#fff; padding:1.6rem 1.5rem; }
    .head h1 { font-family:"Playfair Display",serif; font-size:1.25rem; }
    .head p { font-size:.82rem; opacity:.85; margin-top:.25rem; }
    .body { padding:1.6rem; }
    .fg { margin-bottom:1rem; }
    .fg label { display:block; font-size:.82rem; font-weight:600; color:var(--green-dark); margin-bottom:.35rem; }
    .fc { width:100%; padding:.65rem .9rem; border:1.5px solid var(--border); border-radius:10px; font-family:inherit; font-size:.9rem; }
    .fc:focus { outline:none; border-color:var(--green-main); }
    .err { background:#fdecea; color:#c62828; padding:.65rem .85rem; border-radius:10px; font-size:.85rem; margin-bottom:1rem; }
    .btn { width:100%; padding:.75rem; border:none; border-radius:12px; background:linear-gradient(135deg,var(--green-main),var(--green-dark)); color:#fff; font-weight:700; font-family:inherit; cursor:pointer; font-size:.95rem; }
    .btn:hover { filter:brightness(1.05); }
    .foot { text-align:center; margin-top:1rem; font-size:.82rem; color:#666; }
    .foot a { color:var(--orange); font-weight:600; text-decoration:none; }
  </style>
</head>
<body>
  <div class="card">
    <div class="head">
      <h1>Administration Blog</h1>
      <p>Connexion réservée aux comptes administrateur</p>
    </div>
    <div class="body">
      <?php if ($error !== ''): ?>
        <div class="err"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="post" action="login.php" id="loginForm" novalidate>
        <div class="fg">
          <label for="email">Email</label>
          <input class="fc" type="text" id="email" name="email" autocomplete="username" />
        </div>
        <div class="fg">
          <label for="password">Mot de passe</label>
          <input class="fc" type="password" id="password" name="password" autocomplete="current-password" />
        </div>
        <button class="btn" type="submit">Se connecter</button>
      </form>
      <p class="foot"><a href="../frontoffice/index.html">← Retour au site</a></p>
    </div>
  </div>
  <script>
    document.getElementById("loginForm").addEventListener("submit", function (e) {
      var email = document.getElementById("email").value.trim();
      var pass = document.getElementById("password").value;
      if (!email || !pass) {
        e.preventDefault();
        alert("Veuillez remplir l’email et le mot de passe.");
      }
    });
  </script>
</body>
</html>
