<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
  <style>
    body { font-family:"DM Sans",sans-serif; background:#f2f8ee; min-height:100vh; display:grid; place-items:center; padding:1.5rem; }
    .box { background:#fff; border:1.5px solid #d9eed0; border-radius:16px; padding:2rem; max-width:420px; text-align:center; }
    h1 { font-family:"Playfair Display",serif; color:#2d6a1f; font-size:1.4rem; margin-bottom:.75rem; }
    p { color:#555; margin-bottom:1.25rem; }
    a { color:#4a9e30; font-weight:600; text-decoration:none; }
    a:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <div class="box">
    <h1>Erreur</h1>
    <p><?= htmlspecialchars($error ?? 'Une erreur est survenue.') ?></p>
    <a href="index.php">Retour au blog</a>
  </div>
</body>
</html>
