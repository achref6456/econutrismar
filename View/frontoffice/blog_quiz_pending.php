<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    :root { --bg:#f2f8ee; --green:#2d6a1f; --muted:#5c6b5a; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",system-ui,sans-serif; background:var(--bg); color:#111; min-height:100vh; display:grid; place-items:center; padding:1.5rem; }
    .card { background:#fff; border-radius:16px; padding:2rem; max-width:420px; text-align:center; box-shadow:0 8px 32px rgba(45,106,31,.08); border:1px solid #e4eed9; }
    h1 { font-size:1.1rem; color:var(--green); margin-bottom:.75rem; }
    p { color:var(--muted); font-size:.95rem; line-height:1.5; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Quiz à venir</h1>
    <p>Le quiz associé à ce code n’est pas encore configuré. Revenez plus tard.</p>
  </div>
</body>
</html>
