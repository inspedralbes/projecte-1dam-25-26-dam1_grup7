<?php include_once "header.php";?>
<?php require_once 'connexio.php';?>
<body>
    <h1>Assignar una Incidencia</h1>
    <?php

    //farem un select de la taula departaments i recuperarem una matriu de dades

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM incidencia";
    $result = $conn->query($sql);

    function assignar_incidencia($conn)
    {
        $tecnic = $_POST['tech'];
        $priotitat = $_POST['prio'];

        $sql = "INSERT INTO incidencia (id, prioritat, tecnic) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
        $stmt->bind_param("ss", $tecnic,  $priotitat);

        if ($stmt->execute()) {
        echo "<p class='info'>Incidencia assignada amb èxit!</p>";
        } else {
        echo "<p class='error'>Error al assignar l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
        }
    }

        ?>
        <h1>Consultar Incidencia</h1>
    <?php $sql = "SELECT * FROM incidencia";
    $result = $conn->query($sql); ?>
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
                Modificar
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
        echo "<td style='border: 1px solid black;'>";
        echo "<a href= "."/modificar_incidencia.php?id=" . $row["id"] . ">";
        echo "<button>"."Modificar". "</button>";
        echo "</td>";
        echo "</tr>";
        }
    ?>
    </table>
</body>

</html>

