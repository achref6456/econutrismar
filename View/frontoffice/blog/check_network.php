<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/Model/bootstrap.php';

$modelDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'Model';
$urlFile = $modelDir . DIRECTORY_SEPARATOR . 'local_public_url.txt';
$saveMsg = isset($_GET['ok']) ? 'Adresse enregistrée. Rechargez le blog sur le PC, puis testez le QR sur l’iPhone.' : null;
$saveErr = null;
$formPrefill = '';

if (is_file($urlFile)) {
    $raw = (string) file_get_contents($urlFile);
    $formPrefill = trim((string) (preg_split('/\R/', $raw, 2)[0] ?? ''));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_public_url'])) {
    $v = trim((string) ($_POST['public_base'] ?? ''));
    if ($v !== '' && !preg_match('#^https?://#i', $v)) {
        $saveErr = 'L’URL doit commencer par http:// ou https://';
        $formPrefill = $v;
    } elseif (mb_strlen($v) > 512) {
        $saveErr = 'URL trop longue.';
        $formPrefill = $v;
    } else {
        if ($v === '') {
            if (is_file($urlFile)) {
                @unlink($urlFile);
            }
        } else {
            if (!is_dir($modelDir)) {
                @mkdir($modelDir, 0755, true);
            }
            file_put_contents($urlFile, $v . "\n");
        }
        header('Location: check_network.php?ok=1', true, 303);
        exit;
    }
}

$h = SiteUrl::qrNetworkHelp();
$sample = SiteUrl::absoluteFrontOfficeBase() . '/frontoffice/blog/index.php';
$projectRoot = dirname(__DIR__, 3);
$batAbs = $projectRoot . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . 'Ouvrir_reseau_pour_QR.bat';
$batOk = is_file($batAbs);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QR téléphone — EcoNutri</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; color: #222; }
    h1 { font-size: 1.2rem; color: #2d6a1f; }
    .box { background: #f2f8ee; border: 1px solid #d9eed0; border-radius: 12px; padding: 1rem; margin: 1rem 0; font-size: .9rem; }
    .doit { background: #fff8e6; border: 1px solid #ffe0a3; border-radius: 12px; padding: 1.1rem; margin: 1.25rem 0; }
    .doit strong { display: block; margin-bottom: .5rem; font-size: 1rem; color: #6d4c00; }
    .form-save { background: #fff; border: 2px solid #4a9e30; border-radius: 12px; padding: 1.1rem; margin: 1.25rem 0; }
    .form-save label { display: block; font-weight: 600; margin-bottom: .4rem; color: #2d6a1f; }
    .form-save textarea { width: 100%; min-height: 4.5rem; padding: .6rem; font-size: .88rem; border-radius: 8px; border: 1px solid #ccc; box-sizing: border-box; font-family: ui-monospace, monospace; }
    .form-save button { margin-top: .65rem; padding: .55rem 1.2rem; background: #f07c1b; color: #fff; border: none; border-radius: 50px; font-weight: 700; cursor: pointer; font-size: .9rem; }
    .ok { background: #e8f5e1; border: 1px solid #c8e6c9; color: #1b5e20; padding: .75rem; border-radius: 10px; margin: .75rem 0; }
    .err { background: #ffebee; border: 1px solid #ffcdd2; color: #b71c1c; padding: .75rem; border-radius: 10px; margin: .75rem 0; }
    code { background: #f5f5f5; padding: .15rem .35rem; border-radius: 6px; font-size: .82rem; word-break: break-all; }
    ol { padding-left: 1.2rem; }
    a { color: #4a9e30; font-weight: 600; }
    .ok2 { color: #2e7d32; }
    .warn { color: #c62828; }
  </style>
</head>
<body>
  <h1>Faire fonctionner le QR sur l’iPhone</h1>

  <?php if ($saveMsg): ?>
    <div class="ok"><?= htmlspecialchars($saveMsg) ?></div>
  <?php endif; ?>
  <?php if ($saveErr): ?>
    <div class="err"><?= htmlspecialchars($saveErr) ?></div>
  <?php endif; ?>

  <div class="form-save">
    <form method="post" action="">
      <label for="public_base">Adresse de base du site sur le téléphone (sans chemin vers un fichier)</label>
      <p style="font-size:.82rem;color:#555;margin:0 0 .5rem;">Si sur le PC le blog s’ouvre avec <code>http://localhost:8000/frontoffice/…</code>, mettez la même chose avec l’IP du PC : <code>http://192.168.x.x:8000</code> (sans <code>/frontoffice</code>, sans <code>/View</code>).</p>
      <textarea name="public_base" id="public_base" placeholder="ex. http://192.168.1.10:8000"><?= htmlspecialchars($formPrefill) ?></textarea>
      <button type="submit" name="save_public_url" value="1">Enregistrer pour les QR</button>
    </form>
    <p style="font-size:.78rem;color:#666;margin:.75rem 0 0;">Fichier : <code><?= htmlspecialchars($urlFile) ?></code> — champ vide + enregistrer = effacer.</p>
  </div>

  <div class="doit">
    <strong>Étapes Windows (une fois)</strong>
    <ol>
      <li>Explorateur → projet → <code>View\tools</code> → double-clic <strong>Ouvrir_reseau_pour_QR.bat</strong> → Oui (admin).</li>
      <li>XAMPP : Stop puis Start sur Apache.</li>
      <li>Puis utilisez le formulaire ci-dessus si besoin.</li>
    </ol>
    <?php if ($batOk): ?>
      <p><code><?= htmlspecialchars($batAbs) ?></code></p>
    <?php endif; ?>
  </div>

  <div class="box">
    <p><strong>Adresse utilisée pour les QR :</strong>
      <?= $h['effective_public_base'] !== '' ? '<code>' . htmlspecialchars((string) $h['effective_public_base']) . '</code>' : '<span class="warn">aucune — détection auto</span>' ?></p>
    <p><strong>PC (navigateur) :</strong> <code><?= htmlspecialchars((string) $h['http_origin_browser']) ?></code></p>
    <p><strong>Sans fichier local :</strong> <code><?= htmlspecialchars((string) $h['http_origin_for_qr']) ?></code></p>
    <p><strong>Chemin View :</strong> <code><?= htmlspecialchars((string) $h['view_path']) ?></code></p>
    <p><strong>IP LAN :</strong> <?= $h['lan_ip_candidates'] === [] ? '<span class="warn">aucune</span>' : htmlspecialchars(implode(', ', $h['lan_ip_candidates'])) ?></p>
    <p><strong>Fichier local_public_url.txt :</strong> <?= $h['local_url_file_exists'] ? '<span class="ok2">oui</span>' : '<span class="warn">non</span>' ?></p>
    <p><strong>config public_base_url :</strong> <?= $h['config_public_url_set'] ? '<span class="ok2">oui</span>' : '<span class="warn">non</span>' ?></p>
  </div>

  <p><strong>Test iPhone (même Wi‑Fi) :</strong></p>
  <p><code><?= htmlspecialchars($sample) ?></code></p>

  <p><a href="index.php">← Retour au blog</a></p>
</body>
</html>
