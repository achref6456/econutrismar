<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root { --green-dark:#2d6a1f; --green-main:#4a9e30; --orange:#f07c1b; --bg:#f2f8ee; --border:#e4eed9; --sidebar:#0e2a08; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",sans-serif; background:var(--bg); min-height:100vh; display:flex; color:#111; }
    aside { width:240px; background:var(--sidebar); color:#fff; flex-shrink:0; padding:1.25rem 1rem; display:flex; flex-direction:column; gap:1.5rem; }
    aside .logo { font-family:"Playfair Display",serif; font-size:1.2rem; text-decoration:none; color:#fff; }
    aside .logo span { color:var(--orange); }
    aside .tag { font-size:.62rem; text-transform:uppercase; letter-spacing:.06em; color:rgba(255,255,255,.35); }
    aside nav a { display:block; color:rgba(255,255,255,.75); text-decoration:none; padding:.5rem .6rem; border-radius:8px; font-size:.88rem; margin-bottom:.2rem; }
    aside nav a.active, aside nav a:hover { background:rgba(74,158,48,.25); color:#fff; }
    aside .logout { margin-top:auto; font-size:.85rem; }
    aside .logout a { color:var(--orange); text-decoration:none; }
    main { flex:1; padding:1.75rem 2rem; min-width:0; }
    .top { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.35rem; }
    .btn { display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1.1rem; border-radius:50px; background:var(--orange); color:#fff; text-decoration:none; font-weight:600; font-size:.88rem; border:none; cursor:pointer; font-family:inherit; }
    .flash { background:#e8f5e1; border:1px solid var(--border); color:var(--green-dark); padding:.75rem 1rem; border-radius:12px; margin-bottom:1.25rem; font-size:.9rem; }
    table { width:100%; border-collapse:collapse; background:#fff; border:1.5px solid var(--border); border-radius:14px; overflow:hidden; font-size:.86rem; }
    th, td { text-align:left; padding:.75rem .9rem; border-bottom:1px solid var(--border); vertical-align:middle; }
    th { background:#f9fdf6; font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:#777; }
    tr:last-child td { border-bottom:none; }
    .thumb { width:48px; height:48px; object-fit:cover; border-radius:8px; background:#e8f5e1; }
    .actions { display:flex; gap:.35rem; flex-wrap:wrap; }
    .actions a, .actions button { font-size:.78rem; padding:.35rem .55rem; border-radius:8px; text-decoration:none; border:1.5px solid var(--border); background:#fff; cursor:pointer; font-family:inherit; }
    .actions a:hover { border-color:var(--green-main); color:var(--green-main); }
    .actions .danger:hover { border-color:#e53935; color:#e53935; }
    .empty { background:#fff; border:1.5px dashed var(--border); border-radius:14px; padding:2rem; text-align:center; color:#666; }
    /* Statut badges */
    .statut-badge { display:inline-flex; align-items:center; gap:.25rem; padding:.22rem .6rem; border-radius:50px; font-size:.74rem; font-weight:600; white-space:nowrap; }
    .statut-badge.publie { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
    .statut-badge.brouillon { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }
    .statut-badge.programme { background:#e3f2fd; color:#1565c0; border:1px solid #90caf9; }
    @media (max-width:800px) { body { flex-direction:column; } aside { width:100%; flex-direction:row; flex-wrap:wrap; align-items:center; } aside .logout { margin-top:0; width:100%; } }
  </style>
</head>
<body>
  <aside>
    <div>
      <a class="logo" href="index.php">Eco<span>Nutri</span></a>
      <div class="tag">Back-office</div>
    </div>
    <nav>
      <a class="active" href="index.php">Articles du blog</a>
      <a href="stats.php">Statistiques</a>
      <a href="commentaires.php" style="display:flex;align-items:center;gap:.5rem;">💬 Commentaires <span id="pendingBadge" style="background:#e53935;color:#fff;font-size:.68rem;font-weight:700;padding:.12rem .45rem;border-radius:50px;min-width:18px;text-align:center;display:none;"></span></a>
      <a href="../frontoffice/blog/index.php">Voir le blog (site)</a>
    </nav>
    <div class="logout">
      Connecté : <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?><br />
      <a href="logout.php">Déconnexion</a>
    </div>
  </aside>
  <main>
    <div class="top">
      <h1>Gestion des articles</h1>
      <a class="btn" href="create.php">+ Nouvel article</a>
    </div>
    <?php if (!empty($flash)): ?>
      <div class="flash"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if (count($articles) === 0): ?>
      <div class="empty">Aucun article. Créez votre premier article.</div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr>
              <th></th>
              <th>Titre</th>
              <th>Date</th>
              <th>Statut</th>
              <th>👁️ Vues</th>
              <th>❤️ Likes</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($articles as $a): ?>
              <?php
                $img = trim((string)($a['image'] ?? ''));
                $thumb = $img !== '' ? $assetBase . htmlspecialchars($img) : '';
                $st = (string) ($a['statut'] ?? 'publie');
                $statutLabels = ['publie' => '✅ Publié', 'brouillon' => '📝 Brouillon', 'programme' => '⏰ Programmé'];
                $statutLabel = $statutLabels[$st] ?? $st;
                // Formater la date
                $rawDate = (string) ($a['date_publication'] ?? '');
                $dtObj = DateTime::createFromFormat('Y-m-d H:i:s', $rawDate)
                      ?: DateTime::createFromFormat('Y-m-d\TH:i', $rawDate)
                      ?: DateTime::createFromFormat('Y-m-d H:i', $rawDate)
                      ?: DateTime::createFromFormat('Y-m-d', $rawDate);
                $formattedDate = $dtObj ? $dtObj->format('d/m/Y H:i') : htmlspecialchars($rawDate);
              ?>
              <tr>
                <td>
                  <?php if ($thumb !== ''): ?>
                    <img class="thumb" src="<?= $thumb ?>" alt="" />
                  <?php else: ?>
                    <div class="thumb" style="display:grid;place-items:center;font-size:1.2rem;">📝</div>
                  <?php endif; ?>
                </td>
                <td><strong><?= htmlspecialchars($a['titre']) ?></strong></td>
                <td><?= $formattedDate ?></td>
                <td><span class="statut-badge <?= htmlspecialchars($st) ?>"><?= $statutLabel ?></span></td>
                <td><?= (int) ($a['vues'] ?? 0) ?></td>
                <td><?= (int) ($a['likes'] ?? 0) ?></td>
                <td>
                  <div class="actions">
                    <a href="../frontoffice/blog/article.php?id=<?= (int)$a['id_article'] ?>">Voir</a>
                    <a href="edit.php?id=<?= (int)$a['id_article'] ?>">Modifier</a>
                    <button type="button" class="danger" data-del="<?= (int)$a['id_article'] ?>">Supprimer</button>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </main>
  <script>
    document.querySelectorAll("[data-del]").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var id = btn.getAttribute("data-del");
        if (confirm("Supprimer définitivement cet article ?")) {
          window.location.href = "delete.php?id=" + encodeURIComponent(id);
        }
      });
    });

    // Charger le badge des commentaires en attente
    fetch('../api/admin_commentaires_pending.php')
      .then(r => r.json())
      .then(data => {
        const badge = document.getElementById('pendingBadge');
        if (data.count > 0) {
          badge.textContent = data.count;
          badge.style.display = '';
        }
      })
      .catch(() => {});
  </script>
</body>
</html>
