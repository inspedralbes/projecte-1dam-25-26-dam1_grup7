<?php include_once "header.php"; ?>
<?php require_once 'connexio.php'; ?>

<?php

if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
    ?>

<body>
    <h1>Actuacions</h1>
    <?php $sql = "SELECT * FROM incidencia where id = $id_incidencia";
    $result = $conn->query($sql);

    $cont = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
    $res_cont = $conn->query($cont);
    $row_cont = $res_cont->fetch_assoc();
    $total = $row_cont['total'];

    ?>
    <table>
        <tr style="border: 1px solid black;">
            <th style="border: 1px solid black;">
                ID
            </th>
            <th style="border: 1px solid black;">
                Data Inici
            </th>
            <th style="border: 1px solid black;">
                Prioritat
            </th>
            <th style="border: 1px solid black;">
                Descripció
            </th>
            <th style="border: 1px solid black;">
                Data Fi
            </th>
            <th style="border: 1px solid black;">
                Tecnic Assignat
            </th>
            <th style="border: 1px solid black;">
                Departament
            </th>
            <th style="border: 1px solid black;">
                Tipologia
            </th>
            <th style="border: 1px solid black;">
                Actuacións
            </th>
        </tr>
        <?php
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
        echo "<td style='border: 1px solid black;'>" . $total . "</td>";
        echo "</tr>";
        }
        ?>
    </table>
    <hr>
    <h4>Crear nova actuació</h4>




    <?php

    function crear_actuacions($conn, $id_incidencia){
    // Obtenir el nom de la casa del formulari
    $temps = $_POST['temps'];

    $descripcio = $_POST['desc'];

    $visible = $_POST['visible'];

    if (empty($temps)) {
        echo "<p class='error'>El Temps no pot estar buit.</p>";
        return;
    }
  
    if (empty($descripcio)) {
        echo "<p class='error'>La Descripció no pot estar buida.</p>";
        return;
    }



    // Preparar la consulta SQL per inserir una nova casa
    $sql = "INSERT INTO actuacions (dataActuacio, descActuacio, visible, temps, incidencia) VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
    $stmt->bind_param("siii", $descripcio, $visible, $temps, $id_incidencia);

    // Executar la consulta i comprovar si s'ha inserit correctament
    if ($stmt->execute()) {
        echo "<p class='info'>Actuació creada amb èxit!</p>";
    } else {
        echo "<p class='error'>Error al crear l'actuació: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Tancar la declaració i la connexió
    $stmt->close();

}
    //farem un select de la taula departaments i recuperarem una matriu de dades

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_actuacions($conn, $id_incidencia);

    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>




         <form method="POST" action="crear_actuacions.php?id=<?php echo $id_incidencia; ?>">
            <fieldset>
                <label for="description">Descripció:</label>
                <input type="text" id="desc" name="desc">
                <br>
                <label for="temps">Temps total per l'actuació:</label>
                <input type="number" id="temps" name="temps">
                <br>
                <label for="visible">Visible per l'Usuari?</label>
                <input type="hidden" name="visible" id="no_visible" value="0">
                <input type="checkbox" id="visible" name="visible" value="1">
                <input type="submit" value="Crear">
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>





</body>
</html>