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

    /* ─── Sidebar ─── */
    aside { width:240px; background:var(--sidebar); color:#fff; flex-shrink:0; padding:1.25rem 1rem; display:flex; flex-direction:column; gap:1.5rem; }
    aside .logo { font-family:"Playfair Display",serif; font-size:1.2rem; text-decoration:none; color:#fff; }
    aside .logo span { color:var(--orange); }
    aside .tag { font-size:.62rem; text-transform:uppercase; letter-spacing:.06em; color:rgba(255,255,255,.35); }
    aside nav a { display:flex; align-items:center; gap:.5rem; color:rgba(255,255,255,.75); text-decoration:none; padding:.5rem .6rem; border-radius:8px; font-size:.88rem; margin-bottom:.2rem; }
    aside nav a.active, aside nav a:hover { background:rgba(74,158,48,.25); color:#fff; }
    aside .logout { margin-top:auto; font-size:.85rem; }
    aside .logout a { color:var(--orange); text-decoration:none; }
    .nav-badge { background:#e53935; color:#fff; font-size:.68rem; font-weight:700; padding:.12rem .45rem; border-radius:50px; min-width:18px; text-align:center; line-height:1.3; }

    /* ─── Main ─── */
    main { flex:1; padding:1.75rem 2rem; min-width:0; }
    .top { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.35rem; }

    /* ─── Filters ─── */
    .filters { display:flex; flex-wrap:wrap; gap:.75rem; align-items:center; margin-bottom:1.5rem; }
    .filters select { padding:.5rem .75rem; border-radius:10px; border:1.5px solid var(--border); font-family:inherit; font-size:.85rem; background:#fff; cursor:pointer; }
    .filters select:focus { outline:none; border-color:var(--green-main); }
    .filters .filter-label { font-size:.78rem; font-weight:600; color:#666; text-transform:uppercase; letter-spacing:.03em; }

    /* ─── Table ─── */
    table { width:100%; border-collapse:collapse; background:#fff; border:1.5px solid var(--border); border-radius:14px; overflow:hidden; font-size:.86rem; }
    th, td { text-align:left; padding:.75rem .9rem; border-bottom:1px solid var(--border); vertical-align:middle; }
    th { background:#f9fdf6; font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:#777; }
    tr:last-child td { border-bottom:none; }
    tr { transition:background .15s; }
    tr:hover { background:#f9fdf6; }
    .comment-text { max-width:260px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#333; }
    .article-name { font-weight:500; color:var(--green-dark); }

    /* ─── Statut badges ─── */
    .statut { display:inline-flex; align-items:center; gap:.3rem; padding:.25rem .65rem; border-radius:50px; font-size:.76rem; font-weight:600; white-space:nowrap; }
    .statut.en_attente { background:#fff3e0; color:#e65100; border:1px solid #ffcc80; }
    .statut.approuve { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
    .statut.refuse { background:#ffebee; color:#c62828; border:1px solid #ef9a9a; }

    /* ─── Actions ─── */
    .actions { display:flex; gap:.35rem; flex-wrap:wrap; }
    .actions button { font-size:.82rem; padding:.35rem .55rem; border-radius:8px; border:1.5px solid var(--border); background:#fff; cursor:pointer; font-family:inherit; transition:all .15s; display:inline-flex; align-items:center; gap:.25rem; }
    .actions button:hover { transform:translateY(-1px); box-shadow:0 2px 6px rgba(0,0,0,.08); }
    .actions .approve:hover { border-color:var(--green-main); color:var(--green-main); background:#f0faf0; }
    .actions .refuse:hover { border-color:var(--orange); color:var(--orange); background:#fff8f0; }
    .actions .delete:hover { border-color:#e53935; color:#e53935; background:#fff5f5; }

    .empty { background:#fff; border:1.5px dashed var(--border); border-radius:14px; padding:2rem; text-align:center; color:#666; }
    .flash { background:#e8f5e1; border:1px solid var(--border); color:var(--green-dark); padding:.75rem 1rem; border-radius:12px; margin-bottom:1.25rem; font-size:.9rem; animation:fadeIn .3s ease; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeOut { from{opacity:1} to{opacity:0;transform:translateY(-8px)} }

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
      <a href="index.php">Articles du blog</a>
      <a href="stats.php">Statistiques</a>
      <a class="active" href="commentaires.php">💬 Commentaires <span class="nav-badge" id="sidebarBadge"><?= $pendingCount ?></span></a>
      <a href="../frontoffice/blog/index.php" target="_blank" rel="noopener">Voir le blog (site)</a>
    </nav>
    <div class="logout">
      Connecté : <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?><br />
      <a href="logout.php">Déconnexion</a>
    </div>
  </aside>
  <main>
    <div class="top">
      <h1>Modération des commentaires</h1>
    </div>

    <div id="flashZone"></div>

    <!-- Filtres -->
    <div class="filters">
      <span class="filter-label">🔍 Filtrer :</span>
      <select id="filterStatut">
        <option value="">Tous les statuts</option>
        <option value="en_attente">⏳ En attente</option>
        <option value="approuve">✅ Approuvé</option>
        <option value="refuse">❌ Refusé</option>
      </select>
      <select id="filterArticle">
        <option value="">Tous les articles</option>
      </select>
    </div>

    <!-- Table -->
    <div style="overflow-x:auto;">
      <table id="commentsTable">
        <thead>
          <tr>
            <th>Pseudo</th>
            <th>Commentaire</th>
            <th>Article</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="commentsBody">
          <tr><td colspan="6" class="empty">Chargement…</td></tr>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    const API_BASE = '../api/';
    let allArticles = [];

    /* ─── Charger & afficher ─── */
    function loadComments() {
      const statut    = document.getElementById('filterStatut').value;
      const articleId = document.getElementById('filterArticle').value;

      let url = API_BASE + 'admin_commentaires.php?';
      if (statut)    url += 'statut=' + encodeURIComponent(statut) + '&';
      if (articleId) url += 'article_id=' + encodeURIComponent(articleId) + '&';

      fetch(url)
        .then(r => r.json())
        .then(data => {
          renderTable(data.comments || []);
          // Populate article filter once
          if (allArticles.length === 0 && data.articles) {
            allArticles = data.articles;
            populateArticleFilter(allArticles);
          }
        })
        .catch(err => {
          console.error(err);
          document.getElementById('commentsBody').innerHTML =
            '<tr><td colspan="6" class="empty">Erreur de chargement.</td></tr>';
        });
    }

    function populateArticleFilter(articles) {
      const sel = document.getElementById('filterArticle');
      articles.forEach(a => {
        const opt = document.createElement('option');
        opt.value = a.id_article;
        opt.textContent = a.titre;
        sel.appendChild(opt);
      });
    }

    function renderTable(comments) {
      const tbody = document.getElementById('commentsBody');
      if (comments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="empty">Aucun commentaire trouvé.</td></tr>';
        return;
      }

      tbody.innerHTML = comments.map(c => {
        const d = new Date(c.date_commentaire);
        const dateStr = d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        const pseudo  = escapeHtml(c.pseudo);
        const contenu = escapeHtml(c.contenu);
        const titre   = escapeHtml(c.article_titre || 'Article #' + c.article_id);
        const id      = parseInt(c.id_commentaire, 10);

        const statutLabel = c.statut === 'en_attente' ? '⏳ En attente'
                          : c.statut === 'approuve'   ? '✅ Approuvé'
                          : '❌ Refusé';

        // Actions disponibles selon le statut
        let actionBtns = '';
        if (c.statut === 'en_attente') {
          actionBtns += `<button class="approve" onclick="doAction(${id},'approuver')" title="Approuver">✅</button>`;
          actionBtns += `<button class="refuse"  onclick="doAction(${id},'refuser')" title="Refuser">❌</button>`;
        }
        actionBtns += `<button class="delete" onclick="doDelete(${id})" title="Supprimer">🗑️</button>`;

        return `<tr id="row-${id}">
          <td><strong>${pseudo}</strong></td>
          <td><span class="comment-text" title="${contenu}">"${contenu}"</span></td>
          <td><span class="article-name">${titre}</span></td>
          <td>${dateStr}</td>
          <td><span class="statut ${escapeHtml(c.statut)}">${statutLabel}</span></td>
          <td><div class="actions">${actionBtns}</div></td>
        </tr>`;
      }).join('');
    }

    /* ─── Actions ─── */
    function doAction(id, action) {
      fetch(API_BASE + 'admin_commentaire_action.php?id=' + id + '&action=' + action, { method: 'POST' })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showFlash(action === 'approuver' ? '✅ Commentaire approuvé.' : '❌ Commentaire refusé.');
            loadComments();
            updateBadge();
          }
        })
        .catch(err => console.error(err));
    }

    function doDelete(id) {
      if (!confirm('Supprimer définitivement ce commentaire ?')) return;
      fetch(API_BASE + 'admin_commentaire_action.php?id=' + id + '&action=supprimer', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            showFlash('🗑️ Commentaire supprimé.');
            loadComments();
            updateBadge();
          }
        })
        .catch(err => console.error(err));
    }

    /* ─── Badge sidebar ─── */
    function updateBadge() {
      fetch(API_BASE + 'admin_commentaires_pending.php')
        .then(r => r.json())
        .then(data => {
          const badge = document.getElementById('sidebarBadge');
          badge.textContent = data.count || 0;
          badge.style.display = data.count > 0 ? '' : 'none';
        })
        .catch(() => {});
    }

    /* ─── Flash ─── */
    function showFlash(msg) {
      const zone = document.getElementById('flashZone');
      zone.innerHTML = '<div class="flash">' + msg + '</div>';
      setTimeout(() => {
        const el = zone.querySelector('.flash');
        if (el) {
          el.style.animation = 'fadeOut .3s ease forwards';
          setTimeout(() => zone.innerHTML = '', 350);
        }
      }, 2500);
    }

    /* ─── Helpers ─── */
    function escapeHtml(str) {
      const div = document.createElement('div');
      div.appendChild(document.createTextNode(str || ''));
      return div.innerHTML;
    }

    /* ─── Events ─── */
    document.getElementById('filterStatut').addEventListener('change', loadComments);
    document.getElementById('filterArticle').addEventListener('change', loadComments);

    // Init
    loadComments();

    // Hide badge if 0
    const initBadge = document.getElementById('sidebarBadge');
    if (parseInt(initBadge.textContent, 10) === 0) initBadge.style.display = 'none';
  </script>
</body>
</html>
