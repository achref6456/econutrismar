<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root { --green-dark:#2d6a1f; --green-main:#4a9e30; --orange:#f07c1b; --bg:#f2f8ee; --border:#d9eed0; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",sans-serif; background:var(--bg); color:#111; line-height:1.65; }
    header { background:linear-gradient(135deg,var(--green-dark),var(--green-main)); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
    header a.logo { font-family:"Playfair Display",serif; color:#fff; font-size:1.35rem; text-decoration:none; }
    header a.logo span { color:var(--orange); }
    nav { display:flex; gap:1.25rem; flex-wrap:wrap; }
    nav a { color:rgba(255,255,255,.9); text-decoration:none; font-size:.9rem; }
    nav a.nav-admin { border:1px dashed rgba(255,255,255,.45); padding:.2rem .55rem; border-radius:8px; font-size:.82rem; }
    .wrap { max-width:720px; margin:0 auto; padding:2.5rem 1.5rem; }
    .back { display:inline-block; margin-bottom:1.5rem; color:var(--green-main); font-weight:600; text-decoration:none; font-size:.9rem; }
    .back:hover { text-decoration:underline; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.85rem; margin-bottom:.5rem; }
    .meta { color:#666; font-size:.88rem; margin-bottom:1.5rem; }
    .hero-img { width:100%; border-radius:16px; margin-bottom:1.5rem; border:1.5px solid var(--border); }
    .content { background:#fff; border:1.5px solid var(--border); border-radius:16px; padding:1.5rem 1.35rem; white-space:pre-wrap; }
  </style>
</head>
<body>
  <header>
    <a class="logo" href="../index.html">Eco<span>Nutri</span></a>
    <nav>
      <a href="../index.html">Accueil</a>
      <a href="index.php">Blog</a>
      <a href="search.php">Recherche</a>
      <a class="nav-admin" href="../../backoffice/dev_session.php" title="Session admin sans mot de passe (dev — voir Model/config.php)">Admin blog</a>
    </nav>
  </header>
  <main class="wrap">
    <a class="back" href="index.php">← Retour au blog</a>
    <?php
      $img = trim((string)($article['image'] ?? ''));
      $src = $img !== '' ? $uploadBase . htmlspecialchars($img) : '';
    ?>
    <h1><?= htmlspecialchars($article['titre']) ?></h1>
    <p class="meta"><?= htmlspecialchars($article['date_publication']) ?> · <?= htmlspecialchars(trim(($article['prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?></p>
    <?php if ($src !== ''): ?>
      <img class="hero-img" src="<?= $src ?>" alt="" />
    <?php endif; ?>
    <div class="content"><?= nl2br(htmlspecialchars($article['contenu'])) ?></div>
  </main>
</body>
</html>
