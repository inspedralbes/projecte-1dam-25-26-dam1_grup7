<?php include_once "header.php"; ?>
<?php require_once 'connexio.php'; ?>

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
    $id = $_POST['tecnic'];

    if (empty($id)) {
        echo "<p class='error'>El ID no pot estar buit.</p>";
        return;
    }
    $sql = "SELECT * FROM incidencia WHERE tecnic = '$id'";
    $result = $conn->query($sql);

    echo "<h3> Incidencies </h3>";
    if ($result && $result->num_rows > 0) {
        echo "<table style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='border: 1px solid black; background-color: #f2f2f2;'>";
        echo "<th style='border: 1px solid black;'>ID</th>";
        echo "<th style='border: 1px solid black;'>Data Inici</th>";
        echo "<th style='border: 1px solid black;'>Prioritat</th>";
        echo "<th style='border: 1px solid black;'>Descripció</th>";
        echo "<th style='border: 1px solid black;'>Data Fi</th>";
        echo "<th style='border: 1px solid black;'>Tecnic Assignat</th>";
        echo "<th style='border: 1px solid black;'>Departament</th>";
        echo "<th style='border: 1px solid black;'>Tipologia</th>";
        echo "<th style='border: 1px solid black;'>Actuacións</th>";
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataInici"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["prioritat"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["descripcio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataFi"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tecnic"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["departament"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tipologia"] . "</td>";
            echo "<td style='border: 1px solid black;'>";
            echo "<a href='crear_actuacions.php?id=" . $row["id"] . "'><button>Incidencies</button></a>"; 
            echo "</td>";
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

<body>
    <h1>Crear Actuacions</h1>
    <?php

    //farem un select de la taula departaments i recuperarem una matriu de dades

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        ?>
        <form method="POST" action="actuacio_tecnic.php">
            <fieldset>
                <label for="Tecnic">Id Tecnic:</label>
                <input type="text" id="tecnic" name="tecnic">
                <input type="submit" value="enviar">
            </fieldset>
        </form>
        <?php
    }
    ?>
</body>

</html>

