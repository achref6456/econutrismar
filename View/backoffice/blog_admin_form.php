<?php
$a = is_array($article ?? null) ? $article : [];
$isEdit = isset($a['id_article']);
$idVal = $isEdit ? (int) $a['id_article'] : 0;
$titre = htmlspecialchars((string) ($a['titre'] ?? ''));
$contenu = htmlspecialchars((string) ($a['contenu'] ?? ''));
$rawDate = (string) ($a['date_publication'] ?? '');
// Convertir en format datetime-local (Y-m-d\TH:i)
if ($rawDate !== '') {
    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $rawDate)
       ?: DateTime::createFromFormat('Y-m-d\TH:i', $rawDate)
       ?: DateTime::createFromFormat('Y-m-d', $rawDate);
    $datePub = $dt ? htmlspecialchars($dt->format('Y-m-d\TH:i')) : htmlspecialchars($rawDate);
} else {
    $datePub = htmlspecialchars(date('Y-m-d\TH:i'));
}
$imageUrl = htmlspecialchars((string) ($a['image'] ?? ''));
$statut = htmlspecialchars((string) ($a['statut'] ?? 'publie'));
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
    select.fc { cursor:pointer; appearance:auto; }
    .hint { font-size:.78rem; color:#666; margin-top:.25rem; }
    .row { display:flex; gap:1rem; flex-wrap:wrap; }
    .row .fg { flex:1; min-width:200px; }
    .actions { display:flex; gap:.75rem; margin-top:1.25rem; flex-wrap:wrap; }
    .btn { padding:.6rem 1.3rem; border-radius:50px; border:none; font-family:inherit; font-weight:700; cursor:pointer; font-size:.88rem; }
    .btn-primary { background:linear-gradient(135deg,var(--green-main),var(--green-dark)); color:#fff; }
    .btn-ghost { background:transparent; border:1.5px solid var(--border); color:#555; text-decoration:none; display:inline-flex; align-items:center; }
    .preview { max-width:200px; border-radius:10px; margin-top:.5rem; border:1.5px solid var(--border); }

    /* Statut badges inline */
    .statut-info { display:inline-flex; align-items:center; gap:.3rem; padding:.2rem .55rem; border-radius:50px; font-size:.72rem; font-weight:600; margin-left:.5rem; vertical-align:middle; }
    .statut-info.brouillon { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }
    .statut-info.programme { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
    .statut-info.publie { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }

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
      <a href="../frontoffice/blog/index.php">Voir le site</a>
    </nav>
    <div class="logout"><a href="logout.php">Déconnexion</a></div>
  </aside>
  <main>
    <h1><?= $isEdit ? 'Modifier l\'article' : 'Nouvel article' ?></h1>
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
          <label for="date_publication">📅 Date et heure de publication</label>
          <input class="fc" type="datetime-local" name="date_publication" id="date_publication" value="<?= $datePub ?>" />
          <p class="hint" id="dateHint">Choisissez la date et l'heure de publication.</p>
        </div>
        <div class="fg">
          <label for="statut">📌 Statut</label>
          <select class="fc" name="statut" id="statut">
            <option value="publie" <?= $statut === 'publie' ? 'selected' : '' ?>>✅ Publié</option>
            <option value="brouillon" <?= $statut === 'brouillon' ? 'selected' : '' ?>>📝 Brouillon</option>
            <option value="programme" <?= $statut === 'programme' ? 'selected' : '' ?>>⏰ Programmé</option>
          </select>
          <p class="hint" id="statutHint">
            <?php if ($statut === 'brouillon'): ?>
              L'article ne sera pas visible sur le site.
            <?php elseif ($statut === 'programme'): ?>
              L'article sera publié automatiquement à la date prévue.
            <?php else: ?>
              L'article est visible immédiatement.
            <?php endif; ?>
          </p>
        </div>
      </div>
      <div class="row">
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
        <button class="btn btn-primary" type="submit" id="submitBtn"><?= $isEdit ? 'Enregistrer' : 'Publier' ?></button>
        <a class="btn btn-ghost" href="index.php">Annuler</a>
      </div>
    </form>
  </main>
  <script>
    (function () {
      var form = document.getElementById("articleForm");
      var statutSel = document.getElementById("statut");
      var dateInput = document.getElementById("date_publication");
      var submitBtn = document.getElementById("submitBtn");
      var statutHint = document.getElementById("statutHint");
      var dateHint = document.getElementById("dateHint");
      var isEdit = <?= $isEdit ? 'true' : 'false' ?>;

      // Mettre à jour les hints et le bouton selon le statut
      function updateUI() {
        var s = statutSel.value;
        var hints = {
          'publie': "L'article est visible immédiatement.",
          'brouillon': "L'article ne sera pas visible sur le site.",
          'programme': "L'article sera publié automatiquement à la date prévue."
        };
        statutHint.textContent = hints[s] || '';

        if (s === 'programme') {
          dateHint.textContent = '⏰ La date doit être dans le futur pour la publication programmée.';
          dateHint.style.color = '#1565c0';
        } else {
          dateHint.textContent = "Choisissez la date et l'heure de publication.";
          dateHint.style.color = '#666';
        }

        // Bouton
        if (!isEdit) {
          var labels = { 'publie': 'Publier', 'brouillon': 'Enregistrer le brouillon', 'programme': 'Programmer' };
          submitBtn.textContent = labels[s] || 'Enregistrer';
        }
      }
      statutSel.addEventListener('change', updateUI);
      updateUI();

      // Validation front
      form.addEventListener("submit", function (e) {
        var titre = document.getElementById("titre").value.trim();
        var contenu = document.getElementById("contenu").value.trim();
        var d = dateInput.value.trim();
        var msgs = [];

        if (titre.length < 3) msgs.push("Le titre doit contenir au moins 3 caractères.");
        if (contenu.length < 10) msgs.push("Le contenu doit contenir au moins 10 caractères.");
        if (!d) msgs.push("La date de publication est obligatoire.");

        // Si programmé : vérifier date future
        if (statutSel.value === 'programme' && d) {
          // Parser manuellement pour garantir l'interprétation en heure locale
          var parts = d.split('T');
          var dateParts = parts[0].split('-');
          var timeParts = parts[1].split(':');
          var chosen = new Date(
            parseInt(dateParts[0], 10),
            parseInt(dateParts[1], 10) - 1, // mois 0-indexé
            parseInt(dateParts[2], 10),
            parseInt(timeParts[0], 10),
            parseInt(timeParts[1], 10),
            0
          );
          // Tolérance de 5 minutes pour les petits décalages
          var now = new Date();
          now.setMinutes(now.getMinutes() - 5);
          if (chosen <= now) {
            msgs.push("La date de publication programmée doit être dans le futur.");
          }
        }

        if (msgs.length > 0) {
          e.preventDefault();
          alert(msgs.join("\n"));
        }
      });
    })();
  </script>
</body>
</html>
