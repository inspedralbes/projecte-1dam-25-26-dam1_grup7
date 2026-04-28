<?php include_once "header.php";?>
<?php require_once 'connexio.php';?>
<?php
if (isset($_GET['id'])){
    $id_incidencia = $_GET['id'];
}
?>
    

<body>
    <h1>Assignar una Incidencia</h1>
    <?php

    //farem un select de la taula departaments i recuperarem una matriu de dades
    
    

    function modificar_incidencia($conn)
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
    <?php $sql = "SELECT * FROM incidencia WHERE id = $id_incidencia";
    $result = $conn->query($sql); ?>
    <?php $sqlTech = "SELECT * FROM tecnic";
    $resultTech = $conn->query($sqlTech);
    ?>
    <form>
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
        </tr>
        <?php
       while ($row = $result->fetch_assoc()) {
        echo "<tr style='border: 1px solid black;'>";
        echo "<td style='border: 1px solid black;'>" . $row["id"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["dataInici"] . "</td>";
        echo "<td style='border: 1px solid black;'>";
        echo "<select>";
        
        echo "<option>Baix</option>";
        echo "<option>Mitjà</option>";
        echo "<option>Alta</option>";
        echo "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["descripcio"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["dataFi"] . "</td>";
        echo "<td style='border: 1px solid black;'>";
        echo "<select>";
        
        while ($row = $resultTech->fetch_assoc()) {
            echo "  <option value=" . $row["idDept"] . ">" . $row["nom"] . "</option>" ;
        }
        echo "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["departament"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["tipologia"] . "</td>";
        echo "</tr>";
        }
    ?>
    </table>
    <button>Guardar</button>
</form>
</body>

</html>

