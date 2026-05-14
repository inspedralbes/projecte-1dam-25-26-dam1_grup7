<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>

<?php

if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
?>


<?php
/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    $id_tecnic = $conn->real_escape_string($_POST['tecnic']);

    if (empty($id_tecnic)) {
        echo "<p class='error'>El ID no pot estar buit.</p>";
        return;
    }

    $sql = "SELECT * FROM incidencia WHERE tecnic = '$id_tecnic' AND dataFI IS NULL";
    $result = $conn->query($sql);

    echo "<h3 style='text-align: center;'> Incidències </h3>";
    if ($result && $result->num_rows > 0) {
        echo "<div class='table-container'><table class='modern-table'><thead>";
        echo "<tr style='background-color: var(--tech-main);'>";
        echo "<th>ID</th>";
        echo "<th>Data Inici</th>";
        echo "<th>Prioritat</th>";
        echo "<th>Descripció</th>";
        echo "<th>Data Fi</th>";
        echo "<th>Departament</th>";
        echo "<th>Tipologia</th>";
        echo "<th>Actuacions</th>";
        echo "</tr>";
        echo "</thead>";

        while ($row = $result->fetch_assoc()) {
            $id_incidencia = $row['id'];

            $cont_sql = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
            $res_cont = $conn->query($cont_sql);
            $row_cont = $res_cont->fetch_assoc();
            $total_actuacions = $row_cont['total'];
            if ($row["prioritat"] == "Baix") {
                $status = "stats baixa"; 
            } elseif ($row["prioritat"] == "Mitjà") {
                $status = "stats mitjana"; 
            } elseif ($row["prioritat"] == "Alt") {
                $status = "stats alta"; 
            } else {
                $status = "white"; 
            }
            echo "<tr onclick=\"window.location='crear_actuacions.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["dataInici"] . "</td>";
            echo "<td><span class='$status'>{$row['prioritat']}</span></td>";
            echo "<td>" . $row["descripcio"] . "</td>";
            echo "<td>" . $row["dataFi"] . "</td>";
            echo "<td>" . $row["departament"] . "</td>";
            echo "<td>" . $row["tipologia"] . "</td>";
            echo "<td>" . $total_actuacions . "</td>";
            echo "</tr>";

        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p style='text-align: center;'>No s'han trobat incidències per a aquest tècnic.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear</title>
</head>
<header>
    <a href="tecnics.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Seleccionar incidència</h1>
</header>
<hr>

<body class="page-tecnics">

    <?php

    
    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);
    $sqlTech = "SELECT * FROM tecnic";
    $resultTech = $conn->query($sqlTech);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        ?>
        <main>
            <form method="POST" action="actuacio_tecnic.php">
                <fieldset>
                    <label for="Tecnic">Identifica't:</label>
                    <?php
                    echo "<select name=" . " tecnic" . ">";
                    while ($rowTech = $resultTech->fetch_assoc()) {
                        echo " <option value='" . $rowTech["idTecnic"] . "'>" . $rowTech["nom"] . "</option>";
                    }
                    echo "</select>";
                    ?>
                    <input class="input-tech" type="submit" value="enviar">
                </fieldset>
            </form>
        </main>
        <?php
    }
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>