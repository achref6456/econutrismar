<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    select { padding:0.5rem; border-radius:8px; border:1px solid var(--green-main); font-family:inherit; }
    
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border:1.5px solid var(--border); border-radius:14px; padding:1.5rem; }
    .stat-card h3 { color:var(--green-dark); margin-bottom:1rem; font-size:1.1rem; display:flex; align-items:center; gap:0.5rem; }
    
    .top-list { list-style:none; }
    .top-list li { display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid var(--border); font-size:0.9rem; }
    .top-list li:last-child { border-bottom:none; }
    .top-list .count { font-weight:bold; color:var(--orange); }
    
    .chart-container { background:#fff; border:1.5px solid var(--border); border-radius:14px; padding:1.5rem; }
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
      <a class="active" href="stats.php">Statistiques</a>
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
      <h1>Statistiques détaillées</h1>
      <select id="periodFilter">
        <option value="all">Tout le temps</option>
        <option value="month">Ce mois</option>
        <option value="week">Cette semaine</option>
      </select>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <h3>🏆 Top 5 - Vues</h3>
        <ul class="top-list" id="topViews">
          <li>Chargement...</li>
        </ul>
      </div>
      <div class="stat-card">
        <h3>🏆 Top 5 - Likes</h3>
        <ul class="top-list" id="topLikes">
          <li>Chargement...</li>
        </ul>
      </div>
    </div>

    <div class="chart-container">
      <h3>📊 Vues par article</h3>
      <canvas id="viewsChart"></canvas>
    </div>
  </main>

  <script>
    let myChart = null;

    function renderTops(listId, data) {
      const container = document.getElementById(listId);
      container.innerHTML = '';
      if (data.length === 0) {
        container.innerHTML = '<li>Aucune donnée.</li>';
        return;
      }
      data.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `<span>${item.titre}</span> <span class="count">${item.total}</span>`;
        container.appendChild(li);
      });
    }

    function loadStats(period) {
      fetch('../api/blog_stats.php?period=' + period)
        .then(res => res.json())
        .then(data => {
          renderTops('topViews', data.topViews);
          renderTops('topLikes', data.topLikes);

          const ctx = document.getElementById('viewsChart').getContext('2d');
          if (myChart) {
             myChart.destroy();
          }

          myChart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: data.chartLabels,
              datasets: [{
                label: 'Nombre de vues',
                data: data.chartData,
                backgroundColor: '#4a9e30',
                borderRadius: 4
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
              }
            }
          });
        })
        .catch(err => console.error(err));
    }

    document.getElementById('periodFilter').addEventListener('change', function() {
      loadStats(this.value);
    });

    // Load initial
    loadStats('all');

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

