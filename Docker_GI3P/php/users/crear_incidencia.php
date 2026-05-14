<?php include_once "../globals/header.php"; ?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php');?>
<?php
//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once '../globals/connexio.php';
// Un cop inclòs el fitxer connexio.php, ja podeu utilitzar la variable $conn per a fer les consultes a la base de dades.

/**
 * Funció que llegeix els paràmetres del formulari i crea una nova casa a la base de dades.
 * @param mixed $conn
 * @return void
 */
function crear_incidencia($conn)
{
    // Obtenir el nom de la casa del formulari
    $departament = $_POST['dep'];

    $descripcio = $_POST['desc'];

    if (empty($descripcio)) {
        echo "<script>DescripcioBuida();</script>";
        echo "<script>history.back();</script>";
        return;
    }

    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO incidencia (departament,descripcio,dataInici) VALUES (?,?, NOW())";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("is", $departament, $descripcio);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
        ?>
        <head><meta http-equiv="refresh" content="1;url=usuaris.php"></head>
        <?php
    } else {
        echo "<p class='error'>Error al crear l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }
    registrarLog(); 
    // Tancar la declaració i la connexió
    $stmt->close();

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
    <a href="usuaris.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Crear una Incidència</h1>
    <script src="../js/errors.js"></script>
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
        crear_incidencia($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>
        <main>
            <form method="POST" action="crear_incidencia.php">
                <fieldset>
                    <legend>Nova incidència</legend>

                    <label for="departament">Departament</label>
                    <select name="dep" id="departament">
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row["idDept"]}'>{$row["nom"]}</option>";
                        }
                        ?>
                    </select>

                    <label for="desc">Descripció</label>
                    <textarea id="desc" name="desc" placeholder="Descriu la incidència..."></textarea>

                    <input class="input-user" type="submit" value="Crear incidència">
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