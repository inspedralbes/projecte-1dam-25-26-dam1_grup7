<?php include_once "header.php";?>
<?php

//Sempre volem tenir una connexió a la base de dades, així que la creem al principi del fitxer
require_once 'connexio.php';
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

  
    if (empty($departament)) {
        echo "<p class='error'>El Departament no pot estar buit.</p>";
        return;
    }
  
    if (empty($descripcio)) {
        echo "<p class='error'>La Descripció no pot estar buida.</p>";
        return;
    }

    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO incidencia (departament,descripcio,dataInici) VALUES (?,?, NOW())";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("is", $departament,  $descripcio);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Incidencia creada amb èxit!</p>";
    } else {
        echo "<p class='error'>Error al crear l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
    }

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

<body>
    <h1>Crear una Incidencia</h1>
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
        <form method="POST" action="crear_incidencia.php">
            <fieldset>
                <legend>Incidencia</legend>
              
                <label for="departament">Nom de departament:</label>

                <select name="dep" id="departamen">
                 
            <?php
            while ($row = $result->fetch_assoc()) {
                        echo "  <option value=" . $row["idDept"] . ">" . $row["nom"] . "</option>" ;
                    }
            ?>
                </select>

                <input type="textarea" id="desc" name="desc">
                <input type="submit" value="Crear">
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>
</body>

</html>

