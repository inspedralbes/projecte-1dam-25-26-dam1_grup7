<?php include_once "../globals/header.php"; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php');
registrarLog(); ?>
<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once '../globals/connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */

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


        $total_global = $collection->countDocuments([]);

        $pipeline = [
            ['$group' => ['_id' => '$url', 'count' => ['$sum' => 1]]],
            ['$sort' => ['count' => -1]],
            ['$limit' => 5]
        ];
        $topPagines = $collection->aggregate($pipeline);

        $labels = [];
        $valors = [];
        foreach ($topPagines as $pagina) {
            $labels[] = $pagina['_id'];
            $valors[] = $pagina['count'];
        }
        ?>

        <div class="access-counter-container">
            <div class="access-counter-card">
                <h3>Total d'accessos al lloc web</h3>
                <p class="access-counter-big-number">
                    <?php echo ($total_global); ?>
                </p>
            </div>
        </div>

        <div class="chart-container" style="position: relative; height:40vh; width:80vw; margin: 40px auto;">
            <h3>Top 5 Pàgines més Visitades</h3>
            <canvas id="chartPagines"></canvas>
        </div>
        
        <script>
            const colores = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
            const ctx = document.getElementById('chartPagines').getContext('2d');
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
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
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