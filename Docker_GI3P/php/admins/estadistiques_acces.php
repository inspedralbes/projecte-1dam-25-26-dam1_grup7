<?php include_once "../globals/header.php"; ?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php');
registrarLog();

require_once '../globals/connexio.php';
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadístiques d'Accés</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<header>
    <a href="admin.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>

    <h1>Estadístiques d'Accés</h1>
</header>

<hr>

<body class="page-admin">

<?php

try {

    $client = new MongoDB\Client("mongodb+srv://admin:example@gi3p.rjbxiyc.mongodb.net/?appName=GI3P");
    $collection = $client->Logs->registres_connexio;

    $usuarisDisponibles = $collection->distinct('usuari');

    $filtro = $_GET['temps'] ?? 'tots';

    $fechaInicio = null;

    switch ($filtro) {
        case '24h':
            $fechaInicio = new MongoDB\BSON\UTCDateTime((time() - 86400) * 1000);
            break;

        case '7dies':
            $fechaInicio = new MongoDB\BSON\UTCDateTime((time() - (7 * 86400)) * 1000);
            break;

        case '30dies':
            $fechaInicio = new MongoDB\BSON\UTCDateTime((time() - (30 * 86400)) * 1000);
            break;
    }

    $condicio = [];

    if ($fechaInicio != null) {
        $condicio['timestamp'] = ['$gte' => $fechaInicio];
    }

    if (!empty($_GET['usuari'])) {
        $condicio['usuari'] = $_GET['usuari'];
    }

    $total_global = $collection->countDocuments($condicio);

    $pipeline = [];

    if (!empty($condicio)) {
        $pipeline[] = ['$match' => $condicio];
    }

    $pipeline[] = [
        '$group' => [
            '_id' => '$url',
            'count' => ['$sum' => 1]
        ]
    ];

    $pipeline[] = ['$sort' => ['count' => -1]];
    $pipeline[] = ['$limit' => 5];

    $topPagines = $collection->aggregate($pipeline);

    $labelsPagines = [];
    $valorsPagines = [];

    foreach ($topPagines as $pagina) {
        $labelsPagines[] = $pagina['_id'];
        $valorsPagines[] = $pagina['count'];
    }


    $pipeline2 = [];

    if (!empty($condicio)) {
        $pipeline2[] = ['$match' => $condicio];
    }

    $pipeline2[] = [
        '$group' => [
            '_id' => '$usuari',
            'count' => ['$sum' => 1]
        ]
    ];

    $pipeline2[] = ['$sort' => ['count' => -1]];
    $pipeline2[] = ['$limit' => 5];

    $topUsers = $collection->aggregate($pipeline2);

    $labelsUsers = [];
    $valorsUsers = [];

    foreach ($topUsers as $user) {
        $labelsUsers[] = $user['_id'] ?? 'Desconegut';
        $valorsUsers[] = $user['count'];
    }

?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin:20px 40px; gap:20px;">

    <div class="access-counter-container">
        <div class="access-counter-card">
            <h3>Total d'accessos</h3>
            <p class="access-counter-big-number"><?= $total_global ?></p>
        </div>
    </div>

    <form method="GET" style="display:flex; gap:15px; align-items:center; flex-wrap:wrap; justify-content:flex-end;">

        <div>
            <label><strong>Temps:</strong></label>
            <select name="temps">
                <option value="tots" <?= $filtro == 'tots' ? 'selected' : '' ?>>Tots</option>
                <option value="24h" <?= $filtro == '24h' ? 'selected' : '' ?>>24h</option>
                <option value="7dies" <?= $filtro == '7dies' ? 'selected' : '' ?>>7 dies</option>
                <option value="30dies" <?= $filtro == '30dies' ? 'selected' : '' ?>>30 dies</option>
            </select>

            <label><strong>Usuari:</strong></label>
            <select name="usuari">
                <option value="">Tots</option>
                <?php foreach ($usuarisDisponibles as $u): ?>
                    <option value="<?= $u ?>" <?= (($_GET['usuari'] ?? '') == $u) ? 'selected' : '' ?>>
                        <?= $u ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button id="filtrar-btn" type="submit">Filtrar</button>
        </div>
    </form>

</div>

<div style="display:flex; justify-content:space-between; gap:40px; margin:40px; align-items:flex-start;">

    <div style="flex:1; min-width:400px;">
        <h3>Top 5 Pàgines més Visitades</h3>
        <canvas id="chartPagines"></canvas>
    </div>

    <div style="flex:1; min-width:400px;">
        <h3>Top 5 Users més Actius</h3>
        <canvas id="chartUsers"></canvas>
    </div>

</div>

<script>
const colores = ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF'];

new Chart(document.getElementById('chartPagines'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelsPagines) ?>,
        datasets: [{
            label: 'Visites',
            data: <?= json_encode($valorsPagines) ?>,
            backgroundColor: colores
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<script>
const coloresUsers = ['#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF'];

new Chart(document.getElementById('chartUsers'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelsUsers) ?>,
        datasets: [{
            label: 'Users',
            data: <?= json_encode($valorsUsers) ?>,
            backgroundColor: coloresUsers
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<?php

} catch (Exception $e) {
    echo "<div class='error'>Error al carregar les estadístiques: " . $e->getMessage() . "</div>";
}

?>

</body>

<?php include_once "../globals/footer.php"; ?>
</html>