<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root { --green-dark:#2d6a1f; --green-main:#4a9e30; --orange:#f07c1b; --bg:#f2f8ee; --card:#fff; --border:#d9eed0; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",sans-serif; background:var(--bg); color:#111; }
    header { background:linear-gradient(135deg,var(--green-dark),var(--green-main)); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
    header a.logo { font-family:"Playfair Display",serif; color:#fff; font-size:1.35rem; text-decoration:none; }
    header a.logo span { color:var(--orange); }
    nav { display:flex; gap:1.25rem; flex-wrap:wrap; }
    nav a { color:rgba(255,255,255,.9); text-decoration:none; font-size:.9rem; }
    nav a.nav-admin { border:1px dashed rgba(255,255,255,.45); padding:.2rem .55rem; border-radius:8px; font-size:.82rem; }
    .wrap { max-width:960px; margin:0 auto; padding:2.5rem 1.5rem; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.75rem; margin-bottom:1rem; }
    .search-bar { display:flex; gap:.6rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .search-bar input { flex:1; min-width:200px; padding:.65rem 1rem; border:1.5px solid var(--border); border-radius:10px; }
    .search-bar button { padding:.65rem 1.2rem; background:var(--orange); color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer; }
    .err { color:#c62828; font-size:.88rem; margin-bottom:1rem; }
    .grid { display:grid; gap:1rem; }
    .card { background:var(--card); border:1.5px solid var(--border); border-radius:14px; padding:1rem 1.1rem; }
    .card h2 { font-family:"Playfair Display",serif; font-size:1.05rem; margin-bottom:.35rem; }
    .card h2 a { color:#111; text-decoration:none; }
    .card h2 a:hover { color:var(--green-main); }
    .meta { font-size:.78rem; color:#777; }
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
    <h1>Recherche</h1>
    <form class="search-bar" action="search.php" method="get" id="formSearch">
      <input type="search" name="q" id="q" placeholder="Mots-clés…" value="<?= htmlspecialchars($q) ?>" autocomplete="off" />
      <button type="submit">Rechercher</button>
    </form>
    <?php foreach ($errors as $e): ?>
      <p class="err"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>
    <p id="searchErr" class="err" style="display:none;"></p>
    <?php if ($errors === [] && trim($q) === ''): ?>
      <p style="color:#555;">Entrez un terme pour lancer une recherche dans les titres et le contenu des articles.</p>
    <?php elseif ($errors === [] && count($articles) === 0): ?>
      <p style="color:#555;">Aucun résultat pour « <?= htmlspecialchars($q) ?> ».</p>
    <?php elseif ($errors === []): ?>
      <p style="margin-bottom:1rem;color:#555;"><?= count($articles) ?> résultat(s) pour « <?= htmlspecialchars($q) ?> »</p>
      <div class="grid">
        <?php foreach ($articles as $a): ?>
          <article class="card">
            <h2><a href="article.php?id=<?= (int)$a['id_article'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h2>
            <div class="meta"><?= htmlspecialchars($a['date_publication']) ?></div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>
  <script>
    (function () {
      var form = document.getElementById("formSearch");
      var q = document.getElementById("q");
      var err = document.getElementById("searchErr");
      form.addEventListener("submit", function (e) {
        err.style.display = "none";
        var v = (q.value || "").trim();
        if (v.length > 0 && v.length < 2) {
          e.preventDefault();
          err.textContent = "Saisissez au moins 2 caractères ou laissez vide.";
          err.style.display = "block";
        }
      });
    })();
  </script>
</body>
</html>
