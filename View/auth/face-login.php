<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../Model/User.php';

// Récupérer l'avatar de l'admin pour la comparaison
$userModel = new User();
$admins = [];
$stmt = Database::getInstance()->getPdo()->query(
    "SELECT id, nom, prenom, email, role, avatar FROM users WHERE role = 'admin' AND avatar IS NOT NULL"
);
$admins = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcoNutri – Connexion par visage</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    :root {
      --green-dark:#2d6a1f; --green-main:#4a9e30; --green-light:#7ec44f;
      --green-pale:#e8f5e1; --orange:#f07c1b; --black:#111; --grey:#666;
      --white:#fff; --border:#e4eed9; --bg:#f2f8ee;
      --red:#e53935;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body {
      font-family:"DM Sans",sans-serif;
      min-height:100vh; display:grid; place-items:center; padding:2rem;
      background:linear-gradient(160deg, var(--green-pale) 0%, #d4edbc 55%, #f5fff0 100%);
    }
    .card {
      background:var(--white); border-radius:24px;
      box-shadow:0 20px 60px rgba(45,106,31,0.15);
      width:min(500px, 100%); overflow:hidden;
    }
    .card-head {
      background:linear-gradient(135deg, var(--green-dark), var(--green-main));
      padding:2rem 2.2rem 1.8rem; text-align:center;
    }
    .logo { display:inline-flex; align-items:center; gap:0.6rem; text-decoration:none; margin-bottom:1.2rem; }
    .logo-icon { width:44px; height:44px; background:rgba(255,255,255,0.15); border-radius:12px; display:grid; place-items:center; border:1px solid rgba(255,255,255,0.3); font-size:1.4rem; }
    .logo-text { font-family:"Playfair Display",serif; font-size:1.5rem; color:var(--white); }
    .logo-text span { color:var(--orange); }
    .card-head h1 { font-family:"Playfair Display",serif; font-size:1.3rem; color:var(--white); margin-bottom:0.3rem; }
    .card-head p  { font-size:0.83rem; color:rgba(255,255,255,0.72); }
    .card-body { padding:2rem 2.2rem; }

    /* Caméra */
    .camera-wrap {
      position:relative; border-radius:16px; overflow:hidden;
      background:#000; margin-bottom:1.2rem;
      border:3px solid var(--border);
    }
    #video {
      width:100%; display:block; border-radius:13px;
    }
    #overlay {
      position:absolute; top:0; left:0; width:100%; height:100%;
      pointer-events:none;
    }
    .scan-line {
      position:absolute; top:0; left:0; right:0;
      height:3px;
      background:linear-gradient(90deg, transparent, var(--green-main), transparent);
      animation:scan 2s linear infinite;
    }
    @keyframes scan {
      0%   { top:0; }
      100% { top:100%; }
    }

    /* Status */
    .status-box {
      text-align:center; padding:0.8rem 1rem;
      border-radius:12px; font-size:0.88rem;
      margin-bottom:1rem; font-weight:500;
      transition:all 0.3s;
    }
    .status-loading { background:#f0f0f0; color:var(--grey); }
    .status-scanning { background:var(--green-pale); color:var(--green-dark); }
    .status-success  { background:#e8f5e9; color:#2e7d32; border:1px solid #a5d6a7; }
    .status-error    { background:#fdecea; color:var(--red); border:1px solid #f5c6c6; }

    /* Boutons */
    .btn-start {
      width:100%; padding:0.85rem; border:none; border-radius:50px;
      background:linear-gradient(135deg, var(--green-main), var(--green-dark));
      color:var(--white); font-family:"DM Sans",sans-serif;
      font-size:0.95rem; font-weight:700; cursor:pointer;
      transition:transform 0.2s, box-shadow 0.2s; margin-bottom:0.8rem;
    }
    .btn-start:hover { transform:translateY(-2px); box-shadow:0 8px 22px rgba(45,106,31,0.3); }
    .btn-start:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

    .card-foot { text-align:center; padding:1.2rem 2.2rem 1.8rem; border-top:1px solid var(--border); font-size:0.85rem; color:var(--grey); }
    .card-foot a { color:var(--green-main); font-weight:700; text-decoration:none; }

    /* Cercle de détection */
    .face-circle {
      position:absolute; top:50%; left:50%;
      transform:translate(-50%, -50%);
      width:200px; height:200px;
      border:3px dashed rgba(74,158,48,0.6);
      border-radius:50%;
      pointer-events:none;
    }
    .face-circle.detected {
      border-color:var(--green-main);
      border-style:solid;
      box-shadow:0 0 20px rgba(74,158,48,0.4);
    }
  </style>
</head>
<body>

<div class="card">
  <div class="card-head">
    <a href="index.php" class="logo">
      <div class="logo-icon">🌿</div>
      <span class="logo-text">Eco<span>Nutri</span></span>
    </a>
    <h1>🔐 Connexion par visage</h1>
    <p>Placez votre visage devant la caméra</p>
  </div>

  <div class="card-body">
    <div class="status-box status-loading" id="statusBox">
      ⏳ Chargement des modèles IA...
    </div>

    <div class="camera-wrap">
      <video id="video" autoplay muted playsinline></video>
      <canvas id="overlay"></canvas>
      <div class="scan-line" id="scanLine" style="display:none;"></div>
      <div class="face-circle" id="faceCircle"></div>
    </div>

    <button class="btn-start" id="btnStart" disabled onclick="startRecognition()">
      📷 Démarrer la reconnaissance
    </button>

    <!-- Images de référence des admins (cachées) -->
    <?php foreach ($admins as $admin): ?>
      <?php if (!empty($admin['avatar'])): ?>
        <img id="ref_<?= $admin['id'] ?>"
             src="uploads/avatars/<?= htmlspecialchars($admin['avatar']) ?>"
             data-user-id="<?= $admin['id'] ?>"
             data-user-nom="<?= htmlspecialchars($admin['nom']) ?>"
             data-user-prenom="<?= htmlspecialchars($admin['prenom']) ?>"
             data-user-role="<?= htmlspecialchars($admin['role']) ?>"
             crossorigin="anonymous"
             style="display:none;" />
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="card-foot">
    <a href="index.php?page=login">← Connexion classique</a>
  </div>
</div>

<!-- Formulaire caché pour soumettre la connexion -->
<form id="loginForm" method="POST" action="index.php?page=face-login-process" style="display:none;">
  <input type="hidden" name="user_id" id="hiddenUserId" />
</form>

<script src="face-api.min.js"></script>
<script>
const MODEL_URL = '/econutrismar/models';
let isRunning = false;
let videoStream = null;

async function loadModels() {
  const statusBox = document.getElementById('statusBox');
  try {
    statusBox.className = 'status-box status-loading';
    statusBox.textContent = '⏳ Chargement des modèles IA...';

    await Promise.all([
      faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
      faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
      faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL),
    ]);

    // Démarrer la caméra
    videoStream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
    const video = document.getElementById('video');
    video.srcObject = videoStream;

    await new Promise(resolve => video.onloadedmetadata = resolve);

    statusBox.className = 'status-box status-scanning';
    statusBox.textContent = '✅ Prêt ! Cliquez sur "Démarrer"';
    document.getElementById('btnStart').disabled = false;

  } catch (err) {
    statusBox.className = 'status-box status-error';
    statusBox.textContent = '❌ Erreur : ' + err.message;
  }
}

async function startRecognition() {
  if (isRunning) return;
  isRunning = true;

  const statusBox  = document.getElementById('statusBox');
  const btnStart   = document.getElementById('btnStart');
  const scanLine   = document.getElementById('scanLine');
  const faceCircle = document.getElementById('faceCircle');
  const video      = document.getElementById('video');
  const overlay    = document.getElementById('overlay');

  btnStart.disabled = true;
  scanLine.style.display = 'block';
  statusBox.className = 'status-box status-scanning';
  statusBox.textContent = '🔍 Analyse du visage en cours...';

  // Charger les descripteurs de référence
  const labeledDescriptors = [];
  const refImages = document.querySelectorAll('[id^="ref_"]');

  for (const img of refImages) {
    try {
      const detection = await faceapi
        .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks()
        .withFaceDescriptor();

      if (detection) {
        labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(
          img.dataset.userId,
          [detection.descriptor]
        ));
      }
    } catch(e) {}
  }

  if (labeledDescriptors.length === 0) {
    statusBox.className = 'status-box status-error';
    statusBox.textContent = '❌ Aucun visage de référence trouvé. Ajoutez un avatar à votre compte admin.';
    isRunning = false;
    btnStart.disabled = false;
    return;
  }

  const faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.5);

  // Analyser pendant 5 secondes
  let attempts = 0;
  const maxAttempts = 20;

  const interval = setInterval(async () => {
    attempts++;

    const detection = await faceapi
      .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions())
      .withFaceLandmarks()
      .withFaceDescriptor();

    if (detection) {
      faceCircle.classList.add('detected');
      const match = faceMatcher.findBestMatch(detection.descriptor);

      if (match.label !== 'unknown') {
        clearInterval(interval);
        scanLine.style.display = 'none';

        // Trouver les infos de l'utilisateur
        const refImg = document.getElementById('ref_' + match.label);
        const prenom = refImg ? refImg.dataset.userPrenom : '';
        const nom    = refImg ? refImg.dataset.userNom : '';

        statusBox.className = 'status-box status-success';
        statusBox.innerHTML = `✅ Visage reconnu ! Bonjour <strong>${prenom} ${nom}</strong> 👋<br><small>Connexion en cours...</small>`;

        // Connexion automatique
        setTimeout(() => {
          document.getElementById('hiddenUserId').value = match.label;
          document.getElementById('loginForm').submit();
        }, 1500);
        return;
      }
    } else {
      faceCircle.classList.remove('detected');
    }

    if (attempts >= maxAttempts) {
      clearInterval(interval);
      scanLine.style.display = 'none';
      statusBox.className = 'status-box status-error';
      statusBox.textContent = '❌ Visage non reconnu. Réessayez ou utilisez la connexion classique.';
      isRunning = false;
      btnStart.disabled = false;
    }
  }, 300);
}

// Lancer au chargement
loadModels();
</script>
</body>
</html>
