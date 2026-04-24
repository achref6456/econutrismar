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
    .like-btn { background:none; border:2px solid var(--border); border-radius:50px; padding:.5rem 1.25rem; font-size:1.1rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; margin-bottom:1.5rem; transition:all 0.2s; font-family:"DM Sans",sans-serif; font-weight:600; color:#555; }
    .like-btn:hover { border-color:var(--orange); color:var(--orange); }
    .like-btn.liked { background:#fff0e6; border-color:var(--orange); color:var(--orange); }

    /* ─── Commentaires ─── */
    .comments-section { margin-top:2.5rem; }
    .comments-section h2 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:1.35rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:.5rem; }
    .comments-section h2 .count-badge { background:var(--green-main); color:#fff; font-size:.75rem; padding:.15rem .55rem; border-radius:50px; font-family:"DM Sans",sans-serif; font-weight:600; }

    /* Formulaire */
    .comment-form { background:#fff; border:1.5px solid var(--border); border-radius:16px; padding:1.5rem; margin-bottom:2rem; }
    .comment-form h3 { font-size:1rem; color:var(--green-dark); margin-bottom:1rem; display:flex; align-items:center; gap:.4rem; }
    .comment-form label { display:block; font-weight:600; font-size:.88rem; color:#444; margin-bottom:.3rem; }
    .comment-form input,
    .comment-form textarea { width:100%; padding:.65rem .85rem; border:1.5px solid var(--border); border-radius:10px; font-family:inherit; font-size:.92rem; background:var(--bg); transition:border-color .2s; resize:vertical; }
    .comment-form input:focus,
    .comment-form textarea:focus { outline:none; border-color:var(--green-main); background:#fff; }
    .comment-form textarea { min-height:90px; }
    .comment-form .field { margin-bottom:1rem; }
    .comment-form .submit-btn { background:linear-gradient(135deg,var(--green-dark),var(--green-main)); color:#fff; border:none; padding:.65rem 1.6rem; border-radius:50px; font-weight:600; font-size:.92rem; cursor:pointer; font-family:inherit; transition:opacity .2s, transform .15s; }
    .comment-form .submit-btn:hover { opacity:.9; transform:translateY(-1px); }
    .comment-form .submit-btn:disabled { opacity:.5; cursor:not-allowed; transform:none; }

    /* Messages flash */
    .comment-flash { padding:.75rem 1rem; border-radius:12px; font-size:.9rem; margin-bottom:1rem; animation:fadeIn .3s ease; }
    .comment-flash.success { background:#e8f5e1; border:1px solid var(--border); color:var(--green-dark); }
    .comment-flash.error { background:#fce4e4; border:1px solid #e8b4b4; color:#a33; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

    /* Liste des commentaires */
    .comments-list { display:flex; flex-direction:column; gap:1rem; }
    .comment-card { background:#fff; border:1.5px solid var(--border); border-radius:14px; padding:1.15rem 1.25rem; transition:box-shadow .2s; }
    .comment-card:hover { box-shadow:0 2px 12px rgba(45,106,31,.08); }
    .comment-card .comment-header { display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem; }
    .comment-card .comment-author { font-weight:600; color:var(--green-dark); font-size:.95rem; }
    .comment-card .comment-date { color:#999; font-size:.8rem; margin-left:auto; }
    .comment-card .comment-body { color:#333; font-size:.92rem; line-height:1.6; }
    .no-comments { text-align:center; color:#888; font-size:.92rem; padding:1.5rem; background:#fff; border:1.5px dashed var(--border); border-radius:14px; }
    .comment-errors { list-style:none; }
    .comment-errors li { font-size:.85rem; }
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
    <?php
      $rawPubDate = (string) ($article['date_publication'] ?? '');
      $dtPub = DateTime::createFromFormat('Y-m-d H:i:s', $rawPubDate)
            ?: DateTime::createFromFormat('Y-m-d\TH:i', $rawPubDate)
            ?: DateTime::createFromFormat('Y-m-d', $rawPubDate);
      $displayDate = $dtPub ? $dtPub->format('d/m/Y H:i') : htmlspecialchars($rawPubDate);
    ?>
    <p class="meta"><?= $displayDate ?> · <?= htmlspecialchars(trim(($article['prenom'] ?? '') . ' ' . ($article['auteur_nom'] ?? ''))) ?></p>
    
    <button id="likeBtn" class="like-btn <?= $hasLiked ? 'liked' : '' ?>" data-id="<?= (int)$article['id_article'] ?>">
      <?= $hasLiked ? '❤️ Aimé' : '🤍 J\'aime' ?>
    </button>

    <?php if ($src !== ''): ?>
      <img class="hero-img" src="<?= $src ?>" alt="" />
    <?php endif; ?>
    <div class="content"><?= nl2br(htmlspecialchars($article['contenu'])) ?></div>

    <!-- ═══════════ Section Commentaires ═══════════ -->
    <section class="comments-section">

      <!-- Formulaire -->
      <div class="comment-form">
        <h3>💬 Laisser un commentaire</h3>
        <div id="commentFlash"></div>
        <form id="commentForm">
          <div class="field">
            <label for="commentPseudo">Pseudo</label>
            <input type="text" id="commentPseudo" name="pseudo" placeholder="Votre nom ou pseudo" maxlength="10" pattern="[a-zA-ZÀ-ÿ\s]+" required />
            <p style="font-size:.78rem;color:#888;margin-top:.25rem;">Max 10 caractères, lettres uniquement (pas de chiffres ni caractères spéciaux)</p>
          </div>
          <div class="field">
            <label for="commentContenu">Commentaire</label>
            <textarea id="commentContenu" name="contenu" placeholder="Écrivez votre commentaire..." required></textarea>
          </div>
          <button type="submit" class="submit-btn" id="commentSubmitBtn">Envoyer</button>
        </form>
      </div>

      <!-- Liste des commentaires approuvés -->
      <h2>💬 Commentaires <span class="count-badge" id="commentCount">0</span></h2>
      <div class="comments-list" id="commentsList">
        <div class="no-comments">Chargement des commentaires...</div>
      </div>
    </section>
  </main>

  <script>
    const articleId = <?= (int)$article['id_article'] ?>;
    
    // ─── Enregistrer la vue ───
    fetch('../../api/blog_vue.php?id=' + articleId, { method: 'POST' }).catch(e => console.error(e));

    // ─── Like ───
    const likeBtn = document.getElementById('likeBtn');
    likeBtn.addEventListener('click', () => {
      fetch('../../api/blog_like.php?id=' + articleId, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            if (data.liked) {
              likeBtn.classList.add('liked');
              likeBtn.innerHTML = '❤️ Aimé';
            } else {
              likeBtn.classList.remove('liked');
              likeBtn.innerHTML = '🤍 J\'aime';
            }
          }
        })
        .catch(e => console.error(e));
    });

    // ─── Commentaires : charger la liste ───
    function loadComments() {
      fetch('../../api/blog_commentaires.php?id=' + articleId)
        .then(res => res.json())
        .then(data => {
          const container = document.getElementById('commentsList');
          const badge = document.getElementById('commentCount');
          const comments = data.comments || [];

          badge.textContent = comments.length;

          if (comments.length === 0) {
            container.innerHTML = '<div class="no-comments">Aucun commentaire pour le moment. Soyez le premier à commenter !</div>';
            return;
          }

          container.innerHTML = comments.map(c => {
            const d = new Date(c.date_commentaire);
            const dateStr = d.toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });
            const pseudo = escapeHtml(c.pseudo);
            const body   = escapeHtml(c.contenu);
            return `<div class="comment-card">
              <div class="comment-header">
                <span class="comment-author">👤 ${pseudo}</span>
                <span class="comment-date">${dateStr}</span>
              </div>
              <div class="comment-body">${body.replace(/\n/g, '<br>')}</div>
            </div>`;
          }).join('');
        })
        .catch(err => {
          console.error(err);
          document.getElementById('commentsList').innerHTML = '<div class="no-comments">Impossible de charger les commentaires.</div>';
        });
    }

    // ─── Commentaires : soumettre ───
    document.getElementById('commentForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = document.getElementById('commentSubmitBtn');
      const flash = document.getElementById('commentFlash');
      const pseudo  = document.getElementById('commentPseudo').value.trim();
      const contenu = document.getElementById('commentContenu').value.trim();

      btn.disabled = true;
      btn.textContent = 'Envoi…';

      fetch('../../api/blog_commentaire.php?id=' + articleId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pseudo, contenu })
      })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok || data.errors) {
          const msgs = data.errors || ['Une erreur est survenue.'];
          flash.className = 'comment-flash error';
          flash.innerHTML = '<ul class="comment-errors">' + msgs.map(m => '<li>⚠️ ' + escapeHtml(m) + '</li>').join('') + '</ul>';
        } else {
          flash.className = 'comment-flash success';
          flash.textContent = '✅ Votre commentaire a été soumis et sera visible après modération par l\'admin.';
          document.getElementById('commentPseudo').value = '';
          document.getElementById('commentContenu').value = '';
        }
      })
      .catch(() => {
        flash.className = 'comment-flash error';
        flash.textContent = '❌ Erreur réseau. Veuillez réessayer.';
      })
      .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Envoyer';
      });
    });

    // ─── Helpers ───
    function escapeHtml(str) {
      const div = document.createElement('div');
      div.appendChild(document.createTextNode(str));
      return div.innerHTML;
    }

    // Charger les commentaires au démarrage
    loadComments();
  </script>
</body>
</html>
