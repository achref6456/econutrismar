<?php
$a = is_array($article ?? null) ? $article : [];
$isEdit = isset($a['id_article']);
$idVal = $isEdit ? (int) $a['id_article'] : 0;
$titre = htmlspecialchars((string) ($a['titre'] ?? ''));
$contenu = htmlspecialchars((string) ($a['contenu'] ?? ''));
$datePub = htmlspecialchars((string) ($a['date_publication'] ?? date('Y-m-d')));
$imageUrl = htmlspecialchars((string) ($a['image'] ?? ''));
$action = $isEdit ? 'edit.php' : 'create.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root { --green-dark:#2d6a1f; --green-main:#4a9e30; --orange:#f07c1b; --bg:#f2f8ee; --border:#e4eed9; --sidebar:#0e2a08; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",sans-serif; background:var(--bg); min-height:100vh; display:flex; color:#111; }
    aside { width:240px; background:var(--sidebar); color:#fff; flex-shrink:0; padding:1.25rem 1rem; display:flex; flex-direction:column; gap:1.5rem; }
    aside .logo { font-family:"Playfair Display",serif; font-size:1.2rem; text-decoration:none; color:#fff; }
    aside .logo span { color:var(--orange); }
    aside .tag { font-size:.62rem; text-transform:uppercase; letter-spacing:.06em; color:rgba(255,255,255,.35); }
    aside nav a { display:block; color:rgba(255,255,255,.75); text-decoration:none; padding:.5rem .6rem; border-radius:8px; font-size:.88rem; }
    aside nav a.active { background:rgba(74,158,48,.25); color:#fff; }
    aside .logout { margin-top:auto; font-size:.85rem; }
    aside .logout a { color:var(--orange); text-decoration:none; }
    main { flex:1; padding:1.75rem 2rem; max-width:720px; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.35rem; margin-bottom:1.25rem; }
    .errs { background:#fdecea; color:#b71c1c; padding:.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-size:.88rem; }
    .errs li { margin-left:1.1rem; margin-bottom:.25rem; }
    .fg { margin-bottom:1rem; }
    .fg label { display:block; font-size:.82rem; font-weight:600; color:var(--green-dark); margin-bottom:.35rem; }
    .fc { width:100%; padding:.65rem .9rem; border:1.5px solid var(--border); border-radius:10px; font-family:inherit; font-size:.9rem; background:#fff; }
    textarea.fc { min-height:200px; resize:vertical; }
    .hint { font-size:.78rem; color:#666; margin-top:.25rem; }
    .row { display:flex; gap:1rem; flex-wrap:wrap; }
    .row .fg { flex:1; min-width:200px; }
    .actions { display:flex; gap:.75rem; margin-top:1.25rem; flex-wrap:wrap; }
    .btn { padding:.6rem 1.3rem; border-radius:50px; border:none; font-family:inherit; font-weight:700; cursor:pointer; font-size:.88rem; }
    .btn-primary { background:linear-gradient(135deg,var(--green-main),var(--green-dark)); color:#fff; }
    .btn-ghost { background:transparent; border:1.5px solid var(--border); color:#555; text-decoration:none; display:inline-flex; align-items:center; }
    .preview { max-width:200px; border-radius:10px; margin-top:.5rem; border:1.5px solid var(--border); }
    @media (max-width:800px) { body { flex-direction:column; } aside { width:100%; } }
  </style>
</head>
<body>
  <aside>
    <div>
      <a class="logo" href="index.php">Eco<span>Nutri</span></a>
      <div class="tag">Back-office · Blog</div>
    </div>
    <nav>
      <a class="active" href="index.php">Articles</a>
      <a href="../frontoffice/blog/index.php" target="_blank" rel="noopener">Voir le site</a>
    </nav>
    <div class="logout"><a href="logout.php">Déconnexion</a></div>
  </aside>
  <main>
    <h1><?= $isEdit ? 'Modifier l’article' : 'Nouvel article' ?></h1>
    <?php if (!empty($errors)): ?>
      <ul class="errs">
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data" id="articleForm" novalidate>
      <?php if ($isEdit): ?>
        <input type="hidden" name="id_article" value="<?= $idVal ?>" />
      <?php endif; ?>
      <div class="fg">
        <label for="titre">Titre</label>
        <input class="fc" type="text" name="titre" id="titre" value="<?= $titre ?>" maxlength="255" />
      </div>
      <div class="row">
        <div class="fg">
          <label for="date_publication">Date de publication</label>
          <input class="fc" type="text" name="date_publication" id="date_publication" value="<?= $datePub ?>" placeholder="AAAA-MM-JJ" />
          <p class="hint">Format : AAAA-MM-JJ (ex. 2026-04-12)</p>
        </div>
        <div class="fg">
          <label for="image">Image (URL optionnelle)</label>
          <input class="fc" type="text" name="image" id="image" value="<?= $imageUrl ?>" placeholder="https://… ou vide" />
        </div>
      </div>
      <div class="fg">
        <label for="image_file">Ou téléverser une image (JPG, PNG, WebP)</label>
        <input class="fc" type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp" />
      </div>
      <?php if ($isEdit && trim((string)($a['image'] ?? '')) !== ''): ?>
        <p class="hint">Image actuelle :</p>
        <img class="preview" src="<?= $assetBase . htmlspecialchars((string)$a['image']) ?>" alt="" />
      <?php endif; ?>
      <div class="fg">
        <label for="contenu">Contenu</label>
        <textarea class="fc" name="contenu" id="contenu"><?= $contenu ?></textarea>
      </div>
      <div class="actions">
        <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Enregistrer' : 'Publier' ?></button>
        <a class="btn btn-ghost" href="index.php">Annuler</a>
      </div>
    </form>
  </main>
  <script>
    (function () {
      var form = document.getElementById("articleForm");
      form.addEventListener("submit", function (e) {
        var titre = document.getElementById("titre").value.trim();
        var contenu = document.getElementById("contenu").value.trim();
        var d = document.getElementById("date_publication").value.trim();
        var ok = true;
        if (titre.length < 3) ok = false;
        if (contenu.length < 10) ok = false;
        if (!/^\d{4}-\d{2}-\d{2}$/.test(d)) ok = false;
        if (!ok) {
          e.preventDefault();
          alert("Vérifiez le titre (3 caractères min.), le contenu (10 min.) et la date (AAAA-MM-JJ). La validation serveur affichera aussi les erreurs.");
        }
      });
    })();
  </script>
</body>
</html>
