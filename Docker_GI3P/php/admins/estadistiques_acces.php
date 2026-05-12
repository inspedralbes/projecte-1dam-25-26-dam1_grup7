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

    // FILTRE DE TEMPS
    $filtro = $_GET['temps'] ?? 'tots';

    $fechaInicio = null;

    switch ($filtro) {

        case '24h':
            $fechaInicio = new MongoDB\BSON\UTCDateTime(
                (time() - 86400) * 1000
            );
            break;

        case '7dies':
            $fechaInicio = new MongoDB\BSON\UTCDateTime(
                (time() - (7 * 86400)) * 1000
            );
            break;

        case '30dies':
            $fechaInicio = new MongoDB\BSON\UTCDateTime(
                (time() - (30 * 86400)) * 1000
            );
            break;
    }

    // CONDICIÓ DE FILTRE
    $condicio = [];

    if ($fechaInicio != null) {
        $condicio = [
            'timestamp' => ['$gte' => $fechaInicio]
        ];
    }

    // TOTAL ACCESSOS
    $total_global = $collection->countDocuments($condicio);

    // TOP PÀGINES
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

    $labels = [];
    $valors = [];

    foreach ($topPagines as $pagina) {
        $labels[] = $pagina['_id'];
        $valors[] = $pagina['count'];
    }

?>

    <!-- FILTRE -->
    <form method="GET" style="text-align:center; margin-top:20px;">
        <label for="temps"><strong>Filtrar per temps:</strong></label>

        <select name="temps" id="temps" onchange="this.form.submit()">
            <option value="tots" <?= $filtro == 'tots' ? 'selected' : '' ?>>
                Tots
            </option>

            <option value="24h" <?= $filtro == '24h' ? 'selected' : '' ?>>
                Últimes 24 hores
            </option>

            <option value="7dies" <?= $filtro == '7dies' ? 'selected' : '' ?>>
                Últims 7 dies
            </option>

            <option value="30dies" <?= $filtro == '30dies' ? 'selected' : '' ?>>
                Últims 30 dies
            </option>
        </select>
    </form>

    <!-- TOTAL ACCESSOS -->
    <div class="access-counter-container">
        <div class="access-counter-card">

            <h3>Total d'accessos</h3>

            <p class="access-counter-big-number">
                <?php echo ($total_global); ?>
            </p>

        </div>
    </div>

    <!-- GRÀFIC -->
    <div class="chart-container"
         style="position: relative; height:40vh; width:80vw; margin: 40px auto;">

        <h3>Top 5 Pàgines més Visitades</h3>

        <canvas id="chartPagines"></canvas>
    </div>

    <script>

        const colores = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF'
        ];

        const ctx = document
            .getElementById('chartPagines')
            .getContext('2d');

        new Chart(ctx, {

            type: 'bar',

            data: {

                labels: <?php echo json_encode($labels); ?>,

                datasets: [{

                    label: 'Número de visites',

                    data: <?php echo json_encode($valors); ?>,

                    backgroundColor: colores,

                    borderWidth: 1
                }]
            },

            options: {

                responsive: true,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>

<?php

} catch (Exception $e) {

    echo "
    <div class='error'>
        Error al carregar les estadístiques:
        " . $e->getMessage() . "
    </div>";
}

?>

</body>

<?php include_once "../globals/footer.php"; ?>

</html>