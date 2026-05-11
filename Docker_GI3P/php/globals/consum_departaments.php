<?php include_once "../globals/header.php"; ?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); ?>
<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once '../globals/connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */
function veure_consum($conn)
{
   
    $sql = "SELECT d.nom as nom_dept, COUNT(DISTINCT i.id) as total_incidencies, SUM(a.temps) as total_minuts FROM departament d LEFT JOIN incidencia i ON d.idDept = i.departament LEFT JOIN actuacions a ON i.id = a.incidencia GROUP BY d.idDept HAVING total_incidencies > 0";

    $resultat = $conn->query($sql);

    $noms = [];
    $incidencies = [];
    $hores = [];

    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            $noms[] = $row['nom_dept'];
            $incidencies[] = $row['total_incidencies'];
            $hores[] = round(($row['total_minuts'] ?? 0) / 60, 2);
        }

        $jsonNoms = json_encode($noms);
        $jsonIncidencies = json_encode($incidencies);
        $jsonHores = json_encode($hores);

        echo "<div style='display: flex; flex-wrap: wrap; justify-content: space-around; gap: 20px; margin-top: 30px;'>";
        echo "<div style='width: 400px; text-align: center;'>
                    <h3>Núm. d'Incidències</h3>
                    <canvas id='chartIncidencies'></canvas>
                  </div>";

        echo "<div style='width: 400px; text-align: center;'>
                    <h3>Temps Total (Hores)</h3>
                    <canvas id='chartHores'></canvas>
                  </div>";

        echo "</div>";

        ?>
        <script>
                const colores = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

                new Chart(document.getElementById('chartIncidencies'), {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo $jsonNoms; ?>,
                        datasets: [{
                            data: <?php echo $jsonIncidencies; ?>,
                            backgroundColor: colores
                        }]
                    }
                });
                new Chart(document.getElementById('chartHores'), {
                    type: 'doughnut', 
                    data: {
                        labels: <?php echo $jsonNoms; ?>,
                        datasets: [{
                            label: 'Hores Totals',
                            data: <?php echo $jsonHores; ?>,
                            backgroundColor: colores
                        }]
                    }
                });
        </script>
        <?php
    } else {
        echo "<p class='info'>No hi ha dades disponibles.</p>";
    }

    registrarLog();
}



?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consum de Departaments</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<header>
    <a href="../users/usuaris.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Consum de Departaments</h1>
</header>
<hr>

<body class="page-users">
    <?php

    
    
    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        veure_consum($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>
        <main>
            <form method="POST" action="consum_departaments.php">
                <fieldset>

                    <label for="departament">Departament</label>
                    <select name="dep" id="departament">
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row["idDept"]}'>{$row["nom"]}</option>";
                        }
                        ?>
                    </select>

                    <input type="submit" value="Revisar l'informe">
                </fieldset>
            </form>
        </main>


        <?php
        //Tanquem l'else
    }
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>