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
    body { font-family:"DM Sans",sans-serif; background:var(--bg); color:#111; line-height:1.6; }
    header { background:linear-gradient(135deg,var(--green-dark),var(--green-main)); padding:1rem 2rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
    header a.logo { font-family:"Playfair Display",serif; color:#fff; font-size:1.35rem; text-decoration:none; }
    header a.logo span { color:var(--orange); }
    nav { display:flex; gap:1.25rem; flex-wrap:wrap; }
    nav a { color:rgba(255,255,255,.9); text-decoration:none; font-size:.9rem; font-weight:500; }
    nav a:hover { color:#fff; }
    nav a.nav-admin { border:1px dashed rgba(255,255,255,.45); padding:.2rem .55rem; border-radius:8px; font-size:.82rem; }
    .wrap { max-width:960px; margin:0 auto; padding:2.5rem 1.5rem; }
    h1 { font-family:"Playfair Display",serif; color:var(--green-dark); font-size:2rem; margin-bottom:.5rem; }
    .sub { color:#555; margin-bottom:2rem; }
    .search-bar { display:flex; gap:.6rem; margin-bottom:2rem; flex-wrap:wrap; }
    .search-bar input { flex:1; min-width:200px; padding:.65rem 1rem; border:1.5px solid var(--border); border-radius:10px; font-size:.95rem; }
    .search-bar button { padding:.65rem 1.2rem; background:var(--orange); color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer; }
    .grid { display:grid; gap:1.25rem; }
    .card { background:var(--card); border:1.5px solid var(--border); border-radius:16px; overflow:hidden; display:grid; grid-template-columns:160px 1fr minmax(104px, 124px); }
    .qr-side { padding:.75rem .5rem; border-left:1.5px solid var(--border); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.45rem; text-align:center; background:#fafdf8; }
    .qr-side .qr-side-link { text-decoration:none; display:inline-block; line-height:0; }
    .qr-side .js-qr-inner img, .qr-side .js-qr-inner canvas { width:96px; height:96px; border-radius:10px; border:1px solid var(--border); display:block; }
    .qr-side a:not(.qr-side-link) { font-size:.72rem; color:var(--green-main); font-weight:600; text-decoration:none; }
    .qr-side a:hover { text-decoration:underline; }
    .qr-caption { font-size:.62rem; color:#666; line-height:1.3; max-width:118px; }
    .qr-side--empty { color:#aaa; font-size:1.5rem; }
    .card-img { background:#e8f5e1; min-height:120px; }
    .card-img img { width:100%; height:100%; object-fit:cover; display:block; }
    .card-body { padding:1.1rem 1.25rem; }
    .card-body h2 { font-family:"Playfair Display",serif; font-size:1.15rem; margin-bottom:.4rem; }
    .card-body h2 a { color:#111; text-decoration:none; }
    .card-body h2 a:hover { color:var(--green-main); }
    .meta { font-size:.8rem; color:#777; margin-bottom:.5rem; }
    .excerpt { font-size:.88rem; color:#444; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    .empty { background:#fff; border:1.5px dashed var(--border); border-radius:16px; padding:2rem; text-align:center; color:#666; }
    @media (max-width:720px) {
      .card { grid-template-columns:1fr; }
      .card-img { height:160px; }
      .qr-side { border-left:none; border-top:1.5px solid var(--border); flex-direction:row; flex-wrap:wrap; justify-content:center; padding:1rem; gap:.75rem; }
      .qr-caption { max-width:none; }
    }
  </style>
</head>
<body>
  <header>
    <a class="logo" href="/econutrismar/index.php">Eco<span>Nutri</span></a>
    <nav>
      <a href="/econutrismar/index.php">Accueil</a>
      <a href="/econutrismar/index.php?page=blog">Blog</a>
      <a href="/econutrismar/index.php?page=blog&action=search">Recherche</a>
      <a class="nav-admin" href="/econutrismar/index.php?page=admin_blog">Admin blog</a>
    </nav>
  </header>
  <main class="wrap">
    <h1>Blog</h1>
    <p class="sub">Articles nutrition, recettes et conseils — lecture seule (front-office).</p>
    <form class="search-bar" action="/econutrismar/index.php" method="get" id="formSearch">
      <input type="hidden" name="page" value="blog" />
      <input type="hidden" name="action" value="search" />
      <input type="search" name="q" id="q" placeholder="Rechercher un article…" value="" autocomplete="off" />
      <button type="submit">Rechercher</button>
    </form>
    <p id="searchErr" style="color:#c62828;font-size:.85rem;margin-bottom:1rem;display:none;"></p>
    <?php if (count($articles) === 0): ?>
      <div class="empty">Aucun article pour le moment.</div>
    <?php else: ?>
      <?php
        $needQrLib = false;
        foreach ($articles as $a) {
            if (trim((string) ($a['quiz_token'] ?? '')) !== '') {
                $needQrLib = true;
                break;
            }
        }
      ?>
      <div class="grid">
        <?php foreach ($articles as $a): ?>
          <?php
            $img = trim((string)($a['image'] ?? ''));
            $src = $img !== '' ? '/econutrismar/View/' . htmlspecialchars($img) : 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400&q=80';
          ?>
          <?php
            $qTok = trim((string) ($a['quiz_token'] ?? ''));
            $quizUrl = $qTok !== '' ? SiteUrl::quizAbsoluteUrl($qTok) : '';
          ?>
          <article class="card">
            <div class="card-img">
              <img src="<?= $src ?>" alt="" />
            </div>
            <div class="card-body">
              <h2><a href="/econutrismar/index.php?page=blog&action=show&id=<?= (int)$a['id_article'] ?>"><?= htmlspecialchars($a['titre']) ?></a></h2>
              <div class="meta"><?= htmlspecialchars($a['date_publication']) ?> · <?= htmlspecialchars(trim(($a['prenom'] ?? '') . ' ' . ($a['auteur_nom'] ?? ''))) ?></div>
              <p class="excerpt"><?= htmlspecialchars(mb_substr(strip_tags($a['contenu']), 0, 220)) ?><?= mb_strlen(strip_tags($a['contenu'])) > 220 ? '…' : '' ?></p>
            </div>
            <div class="qr-side">
              <?php if ($quizUrl !== ''): ?>
                <a class="qr-side-link" href="<?= htmlspecialchars($quizUrl) ?>" title="Quiz">
                  <span class="js-qr" data-qr-url="<?= htmlspecialchars($quizUrl) ?>"><span class="js-qr-inner"></span></span>
                </a>
                <p class="qr-caption">Scannez pour le quiz sur cet article</p>
                <a href="<?= htmlspecialchars($quizUrl) ?>">Ouvrir le quiz</a>
              <?php else: ?>
                <span class="qr-side--empty" title="Jeton quiz indisponible">—</span>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <p style="margin-top:2rem;font-size:.82rem;color:#666;text-align:center;">
      <a href="check_network.php">Problème d’accès au blog ou au QR depuis le téléphone ?</a>
    </p>
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
  <?php if ($needQrLib): ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" crossorigin="anonymous"></script>
  <script>
    (function () {
      function run() {
        if (typeof QRCode === "undefined") return;
        document.querySelectorAll(".js-qr[data-qr-url]").forEach(function (w) {
          var url = w.getAttribute("data-qr-url");
          if (!url) return;
          var inner = w.querySelector(".js-qr-inner");
          if (!inner) return;
          inner.innerHTML = "";
          try {
            new QRCode(inner, { text: url, width: 96, height: 96, correctLevel: QRCode.CorrectLevel.L });
          } catch (e) {}
        });
      }
      if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", run);
      else run();
    })();
  </script>
  <?php endif; ?>
</body>
</html>
