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
    function modificar_incidencia($conn, $id_incidencia)
    {
        $tecnic = $_POST['tech'];
        $priotitat = $_POST['prio'];
        $tipologia = $_POST['tipo'];

        $sql = "UPDATE incidencia SET prioritat = ?, tecnic = ?, tipologia = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
        $stmt->bind_param("sssi", $priotitat, $tecnic, $tipologia, $id_incidencia);

        if ($stmt->execute()) {
        echo "<p class='info'>Incidencia assignada amb èxit!</p>";
        } else {
        echo "<p class='error'>Error al assignar l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
        }
    }
        ?>


        <h1>Consultar Incidencia</h1>
    <?php $sql = "SELECT * FROM incidencia WHERE id = $id_incidencia";
    $result = $conn->query($sql); 
    $sqlTech = "SELECT * FROM tecnic";
    $resultTech = $conn->query($sqlTech);
    $sqlTipo = "SELECT * FROM tipologia";
    $resultTipo = $conn->query($sqlTipo);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        modificar_incidencia($conn, $id_incidencia);
    } else {
    ?>

    <form method="POST" action="?id=<?php echo $id_incidencia; ?>">
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
        echo "<select name="."prio". ">";
        
        echo "<option>Baix</option>";
        echo "<option>Mitjà</option>";
        echo "<option>Alt</option>";
        echo "</select>";
        echo "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["descripcio"] . "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["dataFi"] . "</td>";
        echo "<td style='border: 1px solid black;'>";
        echo "<select name="."tech". ">";
        
        while ($rowTech = $resultTech->fetch_assoc()) {
            echo "  <option value='" . $rowTech["idTecnic"] . "'>" .$rowTech["nom"] . "</option>" ;
        }
        echo "</select>";
        echo "</td>";
        echo "<td style='border: 1px solid black;'>" . $row["departament"] . "</td>";
        echo "<td style='border: 1px solid black;'>";
        echo "<select name="."tipo". ">";
        
        while ($rowTipo = $resultTipo->fetch_assoc()) {
            echo "  <option value=" . $rowTipo["idTipo"] . ">" . $rowTipo["nom"] . "</option>" ;
        }
        echo "</select>";
        echo "</td>";
        echo "</tr>";
        }
    ?>
    </table>

    

    <input type="submit" value="Guardar">
</form>

<?php 
    }
    ?>
</body>

</html>

