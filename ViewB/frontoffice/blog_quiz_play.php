<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    :root { --bg:#f2f8ee; --green:#2d6a1f; --green-mid:#4a9e30; --orange:#f07c1b; --card:#fff; --border:#e4eed9; --muted:#5c6b5a; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"DM Sans",system-ui,sans-serif; background:var(--bg); color:#111; min-height:100vh; padding:1.25rem 1rem 2rem; }
    .wrap { max-width:520px; margin:0 auto; }
    .brand { text-align:center; font-size:.78rem; text-transform:uppercase; letter-spacing:.12em; color:var(--muted); margin-bottom:1.25rem; }
    .brand strong { color:var(--green); font-weight:700; }
    .card { background:var(--card); border-radius:18px; padding:1.5rem 1.35rem; border:1.5px solid var(--border); box-shadow:0 10px 40px rgba(14,42,8,.06); }
    .step { display:none; }
    .step.active { display:block; }
    .q-num { font-size:.72rem; font-weight:600; color:var(--green-mid); text-transform:uppercase; letter-spacing:.06em; margin-bottom:.35rem; }
    h2 { font-size:1.05rem; font-weight:600; color:var(--green); line-height:1.35; margin-bottom:1.1rem; }
    .choices { display:flex; flex-direction:column; gap:.55rem; }
    label.choice { display:flex; align-items:flex-start; gap:.65rem; padding:.75rem .85rem; border-radius:12px; border:1.5px solid var(--border); cursor:pointer; font-size:.92rem; line-height:1.35; transition:border-color .15s, background .15s; }
    label.choice:hover { border-color:var(--green-mid); background:#f9fdf6; }
    input[type="radio"] { margin-top:.2rem; accent-color:var(--green-mid); }
    .nav { margin-top:1.35rem; display:flex; justify-content:flex-end; gap:.6rem; flex-wrap:wrap; }
    button { font-family:inherit; font-size:.9rem; font-weight:600; padding:.65rem 1.25rem; border-radius:50px; border:none; cursor:pointer; }
    .btn-next { background:linear-gradient(135deg,var(--green-mid),var(--green)); color:#fff; }
    .btn-next:disabled { opacity:.45; cursor:not-allowed; }
    .result { text-align:center; padding:1rem .5rem; }
    .result .score { font-size:2.25rem; font-weight:700; color:var(--green); line-height:1.2; }
    .result .sub { color:var(--muted); margin-top:.5rem; font-size:.95rem; }
  </style>
</head>
<body>
  <div class="wrap">
    <p class="brand">Eco<strong>Nutri</strong> · Quiz</p>
    <div class="card" id="app">
      <?php for ($i = 0; $i < 3; $i++): ?>
        <?php
          $qi = $questions[$i] ?? null;
          $qtext = htmlspecialchars((string) ($qi['question'] ?? ''));
          $choices = is_array($qi['choices'] ?? null) ? $qi['choices'] : [];
        ?>
        <div class="step" data-step="<?= $i ?>">
          <p class="q-num">Question <?= $i + 1 ?> / 3</p>
          <h2><?= $qtext ?></h2>
          <div class="choices">
            <?php foreach ($choices as $ci => $c): ?>
              <label class="choice">
                <input type="radio" name="q<?= $i ?>" value="<?= (int) $ci ?>" />
                <span><?= htmlspecialchars((string) $c) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
          <div class="nav">
            <?php if ($i < 2): ?>
              <button type="button" class="btn-next" data-next="<?= $i ?>">Suivant</button>
            <?php else: ?>
              <button type="button" class="btn-next" id="btnFinish">Voir mon score</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endfor; ?>
      <div class="step" data-step="3" id="stepResult">
        <div class="result">
          <p class="q-num">Résultat</p>
          <div class="score" id="scoreVal"></div>
          <p class="sub" id="scoreMsg"></p>
        </div>
      </div>
    </div>
  </div>
  <script>
    (function () {
      var correct = <?= json_encode(array_map(static function ($q): int {
          return (int) ($q['correct'] ?? 0);
      }, $questions)) ?>;
      var steps = document.querySelectorAll(".step[data-step]");
      var cur = 0;
      var answers = [null, null, null];

      function show(n) {
        steps.forEach(function (el) {
          el.classList.toggle("active", el.getAttribute("data-step") === String(n));
        });
      }

      function selectedIndex(stepIdx) {
        var r = document.querySelector('input[name="q' + stepIdx + '"]:checked');
        return r ? parseInt(r.value, 10) : null;
      }

      document.querySelectorAll("[data-next]").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var idx = parseInt(btn.getAttribute("data-next"), 10);
          var sel = selectedIndex(idx);
          if (sel === null) {
            return;
          }
          answers[idx] = sel;
          cur = idx + 1;
          show(cur);
        });
      });

      document.getElementById("btnFinish").addEventListener("click", function () {
        var sel = selectedIndex(2);
        if (sel === null) return;
        answers[2] = sel;
        var score = 0;
        for (var i = 0; i < 3; i++) {
          if (answers[i] === correct[i]) score++;
        }
        document.getElementById("scoreVal").textContent = score + " / 3";
        var msg = score === 3 ? "Parfait, bravo !" : score === 0 ? "Vous pourrez retenter une prochaine fois." : "Bon effort !";
        document.getElementById("scoreMsg").textContent = msg;
        show(3);
      });

      show(0);
    })();
  </script>
</body>
</html>
