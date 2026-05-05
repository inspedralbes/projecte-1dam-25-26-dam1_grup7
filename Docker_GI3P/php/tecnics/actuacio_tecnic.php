<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>

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

    echo "<h3> Incidències </h3>";
    if ($result && $result->num_rows > 0) {
        echo "<table style='width: 90%; margin: 20px auto; border-collapse: collapse; border: 1px solid black;'>";
        echo "<tr style='background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>ID</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Data Inici</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Prioritat</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Descripció</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Data Fi</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Departament</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Tipologia</th>";
        echo "<th style='border: 1px solid black; padding: 12px 15px; text-align: left;'>Actuacions</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            $id_incidencia = $row['id'];

            $cont_sql = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
            $res_cont = $conn->query($cont_sql);
            $row_cont = $res_cont->fetch_assoc();
            $total_actuacions = $row_cont['total'];
            echo "<tr onclick=\"window.location='crear_actuacions.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["dataInici"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["prioritat"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["descripcio"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["dataFi"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["departament"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px;'>" . $row["tipologia"] . "</td>";
            echo "<td style='border: 1px solid black; padding: 12px 15px; text-align: center;'>" . $total_actuacions . "</td>";
            echo "</tr>";

        }
        echo "</table>";
    } else {
        echo "<p>No s'han trobat incidències per a aquest tècnic.</p>";
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
    <a href="javascript:history.back()" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Seleccionar incidència</h1>
</header>
<hr>

<body>

    <?php

    //farem un select de la taula departaments i recuperarem una matriu de dades
    
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
                    echo "<select name="." tecnic". ">" ; while ($rowTech=$resultTech->fetch_assoc()) {
                        echo " <option value='" . $rowTech["idTecnic"] . "'>" .$rowTech["nom"] . "</option>" ;
                        }
                        echo "</select>";
                    ?>
                    <input type="submit" value="enviar">
                </fieldset>
            </form>
        </main>
        <?php
    }
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>