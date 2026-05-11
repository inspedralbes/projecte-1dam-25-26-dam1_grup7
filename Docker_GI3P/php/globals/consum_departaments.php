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
    $sql = "SELECT d.nom as nom_dept, COUNT(i.id) as total_incidencies FROM incidencia i INNER JOIN departament d ON i.departament = d.idDept GROUP BY d.idDept";

    $resultat = $conn->query($sql);
    $noms = [];
    $totals = [];

    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            $noms[] = $row['nom_dept'];
            $totals[] = $row['total_incidencies'];
        }

        $jsonNoms = json_encode($noms);
        $jsonTotals = json_encode($totals);

        echo "<h3>Distribució d'Incidències per Departament</h3>";
        echo "<div style='max-width: 450px; margin: auto;'><canvas id='myChart'></canvas></div>";

        ?>
        <script>
            const ctx = document.getElementById('myChart');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo $jsonNoms; ?>,
                    datasets: [{
                        label: 'Total Incidències',
                        data: <?php echo $jsonTotals; ?>,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        </script>
        <?php
    } else {
        echo "<p class='info'>No hi ha dades d'incidències.</p>";
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

    //farem un select de la taula departaments i recuperarem una matriu de dades
    
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