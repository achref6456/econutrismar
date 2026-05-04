<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Mon Profil</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    /* ── Light mode (défaut) ── */
    :root {
      --green-dark:#2d6a1f; --green-main:#4a9e30; --green-light:#7ec44f;
      --green-pale:#e8f5e1; --orange:#f07c1b; --black:#111; --grey:#666;
      --white:#fff; --border:#e4eed9; --bg:#f2f8ee;
      --red:#e53935; --red-light:#fdecea;
      --card-bg:#fff; --text:#111; --text-muted:#666;
    }
    /* ── Dark mode ── */
    [data-theme="dark"] {
      --bg:#0f1a0d;
      --white:#1a2e17;
      --card-bg:#1a2e17;
      --border:#2a4a24;
      --black:#e8f5e1;
      --grey:#9ab89a;
      --text:#e8f5e1;
      --text-muted:#9ab89a;
      --green-pale:#1a3318;
      --red-light:#2a1515;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body {
      font-family:"DM Sans",sans-serif;
      min-height:100vh; background:var(--bg);
      display:grid; place-items:center; padding:2rem;
      transition:background 0.3s, color 0.3s;
    }

    /* ── Bouton dark/light ── */
    .theme-toggle {
      position:fixed; top:1.2rem; right:1.2rem;
      width:44px; height:44px;
      border-radius:50%;
      background:var(--card-bg);
      border:2px solid var(--border);
      cursor:pointer;
      display:grid; place-items:center;
      font-size:1.3rem;
      box-shadow:0 4px 14px rgba(0,0,0,0.15);
      transition:all 0.3s;
      z-index:100;
    }
    .theme-toggle:hover { transform:scale(1.1); border-color:var(--green-main); }
    .card {
      background:var(--card-bg); border-radius:24px;
      box-shadow:0 20px 60px rgba(45,106,31,0.15);
      width:min(600px, 100%); overflow:hidden;
      transition:background 0.3s;
    }
    .card-head {
      background:linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding:2rem 2.2rem; text-align:center;
    }
    .avatar-wrap {
      position:relative; display:inline-block; margin-bottom:1rem;
    }
    .avatar-img {
      width:90px; height:90px; border-radius:50%;
      object-fit:cover; border:4px solid rgba(255,255,255,0.4);
    }
    .avatar-placeholder {
      width:90px; height:90px; border-radius:50%;
      background:rgba(255,255,255,0.2);
      border:4px solid rgba(255,255,255,0.4);
      display:grid; place-items:center;
      font-size:2rem; color:var(--white);
      font-family:"Playfair Display",serif;
      font-weight:700;
    }
    .card-head h1 { font-family:"Playfair Display",serif; font-size:1.3rem; color:var(--white); margin-bottom:0.2rem; }
    .card-head p  { font-size:0.83rem; color:rgba(255,255,255,0.72); }
    .card-body { padding:2rem 2.2rem; }
    .alert { padding:0.8rem 1rem; border-radius:10px; font-size:0.85rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:0.5rem; }
    .alert-success { background:var(--green-pale); color:var(--green-dark); border:1px solid #c3e6a8; }
    .alert-error   { background:var(--red-light);  color:var(--red);        border:1px solid #f5c6c6; }
    .info-row { display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid var(--border); font-size:0.88rem; }
    .info-row:last-of-type { border-bottom:none; }
    .info-label { color:var(--grey); font-weight:500; }
    .info-val   { color:var(--black); font-weight:600; }
    .divider { height:1px; background:var(--border); margin:1.5rem 0; }
    .fg { margin-bottom:1rem; }
    .fg label { display:block; font-size:0.82rem; font-weight:600; color:var(--green-dark); margin-bottom:0.38rem; }
    .fc { width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--border); border-radius:10px; font-family:"DM Sans",sans-serif; font-size:0.88rem; color:var(--black); outline:none; background:var(--bg); }
    .fc.is-error { border-color:var(--red); background:var(--red-light); }
    .field-error { font-size:0.76rem; color:var(--red); margin-top:0.3rem; }
    .avatar-preview-wrap { display:flex; align-items:center; gap:1rem; margin-bottom:0.5rem; }
    .avatar-preview { width:60px; height:60px; border-radius:50%; object-fit:cover; border:2px solid var(--green-main); }
    .avatar-preview-placeholder { width:60px; height:60px; border-radius:50%; background:var(--green-pale); border:2px solid var(--border); display:grid; place-items:center; font-size:1.4rem; }
    .remove-label { display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:var(--red); cursor:pointer; }
    .btn-save { width:100%; padding:0.8rem; border:none; border-radius:50px; background:linear-gradient(135deg,var(--green-main),var(--green-dark)); color:var(--white); font-family:"DM Sans",sans-serif; font-size:0.95rem; font-weight:700; cursor:pointer; transition:transform 0.2s; margin-top:0.5rem; }
    .btn-save:hover { transform:translateY(-2px); }
    /* Calendrier */
    .calendar { margin-top:0; }
    .cal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
    .cal-header h3 { font-family:"Playfair Display",serif; font-size:1rem; color:var(--black); }
    .cal-nav { background:none; border:1.5px solid var(--border); border-radius:8px; width:30px; height:30px; cursor:pointer; font-size:1rem; display:grid; place-items:center; transition:all 0.2s; }
    .cal-nav:hover { background:var(--green-pale); border-color:var(--green-main); }
    .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
    .cal-day-name { text-align:center; font-size:0.7rem; font-weight:700; color:var(--grey-light); padding:0.3rem 0; text-transform:uppercase; }
    .cal-day { text-align:center; padding:0.4rem; border-radius:8px; font-size:0.82rem; cursor:default; transition:all 0.2s; }
    .cal-day.empty { background:transparent; }
    .cal-day.normal:hover { background:var(--green-pale); }
    .cal-day.today { background:var(--green-main); color:#fff; font-weight:700; border-radius:50%; }
    .cal-day.joined { background:var(--orange-light); color:var(--orange); font-weight:700; border-radius:50%; }
    .cal-legend { display:flex; gap:1rem; margin-top:0.8rem; font-size:0.75rem; color:var(--grey); }
    .cal-legend span { display:flex; align-items:center; gap:0.3rem; }
    .cal-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }

    /* ── QR Code ── */
    .qr-section { text-align:center; }
    .qr-section h3 { font-family:"Playfair Display",serif; font-size:1rem; color:var(--black); margin-bottom:1rem; }
    .qr-card { display:inline-block; background:var(--white); border:2px solid var(--border); border-radius:20px; padding:1.5rem 2rem; box-shadow:0 8px 28px rgba(45,106,31,0.12); min-width:240px; }
    .qr-card-header { display:flex; align-items:center; justify-content:center; gap:0.6rem; margin-bottom:1rem; padding-bottom:0.8rem; border-bottom:1.5px solid var(--border); }
    .qr-logo-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--green-dark),var(--green-main)); border-radius:10px; display:grid; place-items:center; font-size:1.2rem; }
    .qr-logo-text { font-family:"Playfair Display",serif; font-size:1.2rem; color:var(--green-dark); font-weight:700; }
    .qr-logo-text span { color:var(--orange); }
    .qr-img-wrap { display:flex; justify-content:center; margin-bottom:0.5rem; }
    .qr-label { font-size:0.75rem; color:var(--grey); margin-top:0.4rem; }
    .qr-card-footer { margin-top:0.8rem; padding-top:0.8rem; border-top:1.5px solid var(--border); font-size:0.78rem; color:var(--green-dark); font-weight:600; }
    .btn-dl-qr { display:inline-flex; align-items:center; gap:0.4rem; margin-top:1rem; padding:0.5rem 1.2rem; border:1.5px solid var(--green-main); border-radius:50px; background:var(--white); color:var(--green-dark); font-family:"DM Sans",sans-serif; font-size:0.83rem; font-weight:600; cursor:pointer; text-decoration:none; transition:all 0.2s; }
    .btn-dl-qr:hover { background:var(--green-pale); }
    .map-section h3 { font-family:"Playfair Display",serif; font-size:1rem; color:var(--black); margin-bottom:0.8rem; }
    .map-wrap { border-radius:14px; overflow:hidden; border:2px solid var(--border); height:260px; background:var(--bg); display:flex; align-items:center; justify-content:center; }
    .map-wrap iframe { width:100%; height:100%; border:none; }
    .map-info { font-size:0.78rem; color:var(--grey); margin-top:0.5rem; display:flex; align-items:center; gap:0.4rem; }

    /* ── btn-back ── */
    .btn-back { display:inline-flex; align-items:center; gap:0.4rem; margin-bottom:1.2rem; padding:0.45rem 1rem; border:1.5px solid var(--border); border-radius:50px; background:var(--white); color:var(--grey); font-size:0.83rem; text-decoration:none; transition:all 0.2s; }
    .btn-back:hover { background:var(--green-pale); border-color:var(--green-main); color:var(--green-dark); }

    /* ── card-foot ── */
    .card-foot { text-align:center; padding:1.2rem 2.2rem 1.8rem; border-top:1px solid var(--border); }
  </style>
</head>
<body>

<!-- Bouton Dark/Light -->
<button class="theme-toggle" onclick="toggleTheme()" id="themeBtn" title="Changer le thème">🌙</button>

<div class="card">
  <div class="card-head">
    <div class="avatar-wrap">
      <?php if (!empty($user['avatar'])): ?>
        <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" class="avatar-img" id="headAvatar" />
      <?php else: ?>
        <div class="avatar-placeholder" id="headAvatar"><?= strtoupper(substr($user['prenom'], 0, 1)) ?></div>
      <?php endif; ?>
    </div>
    <h1><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
    <p><?= $user['role'] === 'admin' ? '⭐ Administrateur' : '👤 Utilisateur' ?></p>
  </div>

  <div class="card-body">

    <a href="index.php?page=frontoffice" class="btn-back">← Retour à l'accueil</a>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Infos du compte -->
    <div class="info-row"><span class="info-label">Nom</span><span class="info-val"><?= htmlspecialchars($user['nom']) ?></span></div>
    <div class="info-row"><span class="info-label">Prénom</span><span class="info-val"><?= htmlspecialchars($user['prenom']) ?></span></div>
    <div class="info-row"><span class="info-label">Email</span><span class="info-val"><?= htmlspecialchars($user['email']) ?></span></div>
    <div class="info-row"><span class="info-label">Membre depuis</span><span class="info-val"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span></div>

    <div class="divider"></div>

    <!-- Calendrier intégré -->
    <div class="calendar">
      <div class="cal-header">
        <button class="cal-nav" onclick="changeMonth(-1)">‹</button>
        <h3 id="calTitle">📅 Calendrier</h3>
        <button class="cal-nav" onclick="changeMonth(1)">›</button>
      </div>
      <div class="cal-grid" id="calDayNames"></div>
      <div class="cal-grid" id="calDays" style="margin-top:4px;"></div>
      <div class="cal-legend">
        <span><span class="cal-dot" style="background:var(--green-main);"></span> Aujourd'hui</span>
        <span><span class="cal-dot" style="background:var(--orange);"></span> Date d'inscription</span>
      </div>
    </div>

    <div class="divider"></div>

    <!-- ── QR Code du profil ── -->
    <div class="qr-section">
      <h3>📱 Mon QR Code</h3>
      <div class="qr-card">
        <!-- Header avec logo -->
        <div class="qr-card-header">
          <div class="qr-logo-icon">🌿</div>
          <span class="qr-logo-text">Eco<span>Nutri</span></span>
        </div>
        <!-- QR Code -->
        <div class="qr-img-wrap">
          <?php
            $carteUrl = "http://" . $_SERVER['HTTP_HOST'] . "/econutrismar/index.php?page=carte&id=" . $user['id'];
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&color=2d6a1f&bgcolor=ffffff&qzone=2&data=" . urlencode($carteUrl);
          ?>
          <img src="<?= $qrUrl ?>" alt="QR Code profil" width="200" height="200" style="display:block;border-radius:8px;" />
        </div>
        <div class="qr-label">Scannez pour voir mon profil</div>
        <!-- Footer -->
        <div class="qr-card-footer">
          🌿 <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
        </div>
      </div>
      <br>
      <a href="<?= $qrUrl ?>&format=png" download="qr-econutri.png" class="btn-dl-qr">⬇️ Télécharger le QR Code</a>
    </div>

    <div class="divider"></div>

    <!-- ── Carte Maps ── -->
    <div class="map-section">
      <h3>📍 Notre adresse</h3>
      <div class="map-wrap">
        <iframe
          src="https://www.openstreetmap.org/export/embed.html?bbox=10.1%2C36.7%2C10.3%2C36.9&layer=mapnik&marker=36.8065%2C10.1815"
          allowfullscreen
          loading="lazy"
          title="Carte EcoNutri Tunis">
        </iframe>
      </div>
      <div class="map-info">📌 EcoNutri — Tunis, Tunisie &nbsp;|&nbsp; <a href="https://www.openstreetmap.org/?mlat=36.8065&mlon=10.1815#map=14/36.8065/10.1815" target="_blank" style="color:var(--green-main);">Ouvrir dans Maps →</a></div>
    </div>

    <div class="divider"></div>

    <!-- Formulaire photo -->
    <form method="POST" action="index.php?page=profil" novalidate enctype="multipart/form-data">

      <div class="fg">
        <label>Photo de profil</label>
        <div class="avatar-preview-wrap">
          <?php if (!empty($user['avatar'])): ?>
            <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>"
                 class="avatar-preview" id="avatarPreview" />
          <?php else: ?>
            <div class="avatar-preview-placeholder" id="avatarPreview">🌿</div>
          <?php endif; ?>
          <div style="flex:1;">
            <input type="file" name="avatar" accept="image/*"
              class="fc <?= !empty($errors['avatar']) ? 'is-error' : '' ?>"
              style="padding:0.5rem;"
              onchange="previewAvatar(this)" />
            <div style="font-size:0.74rem;color:var(--grey);margin-top:0.3rem;">JPG, PNG, GIF — max 2 Mo</div>
          </div>
        </div>
        <?php if (!empty($user['avatar'])): ?>
          <label class="remove-label">
            <input type="checkbox" name="remove_avatar" value="1" />
            Supprimer la photo actuelle
          </label>
        <?php endif; ?>
        <?php if (!empty($errors['avatar'])): ?>
          <div class="field-error">⚠ <?= htmlspecialchars($errors['avatar']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn-save">💾 Enregistrer la photo</button>
    </form>

  </div>

  <div class="card-foot">
    <a href="index.php?page=logout" style="color:var(--red);font-size:0.85rem;text-decoration:none;font-weight:600;">🚪 Se déconnecter</a>
  </div>
</div>

<!-- QR Code library (local) -->
<script src="/econutrismar/qrcode.min.js"></script>
<script>
function previewAvatar(input) {
  const preview = document.getElementById('avatarPreview');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.outerHTML = `<img src="${e.target.result}" class="avatar-preview" id="avatarPreview" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--green-main);" />`;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// ── Calendrier ────────────────────────────────────────────────────────────
const joinedDate = new Date('<?= $user['created_at'] ?>');
const today      = new Date();
let   curYear    = today.getFullYear();
let   curMonth   = today.getMonth();

const dayNames = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];

function renderCalendar() {
  const title = document.getElementById('calTitle');
  const months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
  title.textContent = '📅 ' + months[curMonth] + ' ' + curYear;

  // En-têtes jours
  const namesEl = document.getElementById('calDayNames');
  namesEl.innerHTML = '';
  dayNames.forEach(d => {
    const el = document.createElement('div');
    el.className = 'cal-day-name';
    el.textContent = d;
    namesEl.appendChild(el);
  });

  // Jours du mois
  const daysEl = document.getElementById('calDays');
  daysEl.innerHTML = '';

  const firstDay = new Date(curYear, curMonth, 1).getDay();
  const offset   = (firstDay === 0) ? 6 : firstDay - 1;
  const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();

  // Cases vides
  for (let i = 0; i < offset; i++) {
    const el = document.createElement('div');
    el.className = 'cal-day empty';
    daysEl.appendChild(el);
  }

  // Jours
  for (let d = 1; d <= daysInMonth; d++) {
    const el  = document.createElement('div');
    const isToday   = (d === today.getDate() && curMonth === today.getMonth() && curYear === today.getFullYear());
    const isJoined  = (d === joinedDate.getDate() && curMonth === joinedDate.getMonth() && curYear === joinedDate.getFullYear());

    if (isToday)        el.className = 'cal-day today';
    else if (isJoined)  el.className = 'cal-day joined';
    else                el.className = 'cal-day normal';

    el.textContent = d;
    daysEl.appendChild(el);
  }
}

function changeMonth(dir) {
  curMonth += dir;
  if (curMonth > 11) { curMonth = 0;  curYear++; }
  if (curMonth < 0)  { curMonth = 11; curYear--; }
  renderCalendar();
}

renderCalendar();

// ── Dark / Light Mode ────────────────────────────────────────────────────
const themeBtn = document.getElementById('themeBtn');
const htmlEl   = document.documentElement;

const savedTheme = localStorage.getItem('theme') || 'light';
htmlEl.setAttribute('data-theme', savedTheme);
themeBtn.textContent = savedTheme === 'dark' ? '☀️' : '🌙';

function toggleTheme() {
  const current = htmlEl.getAttribute('data-theme');
  const next    = current === 'dark' ? 'light' : 'dark';
  htmlEl.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  themeBtn.textContent = next === 'dark' ? '☀️' : '🌙';
}
</script>
</body>
</html>
