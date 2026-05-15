<?php include_once "../globals/header.php";?>
<?php require_once '../globals/connexio.php';?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php');?>
<?php
if (isset($_GET['id'])){
    $id_incidencia = $_GET['id'];
}
?>
<html>
<header>
    <a href="assignar_incidencia.php" class="btn-back">
            <span class="arrow">←</span> Tornar
        </a>
        <h1>Modificar Incidencia</h1>
</header>
    <hr>

<body class="page-admin">
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
        ?>
        <head><meta http-equiv="refresh" content="1;url=assignar_incidencia.php"></head>
        <?php
        } else {
        echo "<p class='error'>Error al assignar l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
        }
        registrarLog();
    }
        ?>
    <?php $sql = "SELECT * FROM incidencia WHERE id = $id_incidencia";
    $result = $conn->query($sql); 
    $sqlTech = "SELECT * FROM Users WHERE Rol = 'tecnic'";
    $resultTech = $conn->query($sqlTech);
    $sqlTipo = "SELECT * FROM tipologia";
    $resultTipo = $conn->query($sqlTipo);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        modificar_incidencia($conn, $id_incidencia);
    } else {
    ?>
    <main>
    <form class="ignore-css" method="POST" action="?id=<?php echo $id_incidencia;?>">
    <div class="table-container">
    <table class="modern-table">
    <thead>
        <tr style="background-color: var(--admin-main);">
            <th>
                ID
            </th>
            <th>
                Data Inici
            </th>
            <th>
                Prioritat
            </th>
            <th>
                Descripció
            </th>
            <th>
                Data Fi
            </th>
            <th>
                Tecnic Assignat
            </th>
            <th>
                Departament
            </th>
            <th>
                Tipologia
            </th>
        </tr>
</thead>
        <?php
       while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["dataInici"] . "</td>";
        echo "<td>";
        echo "<select name="."prio". ">";
        
        echo "<option>Baix</option>";
        echo "<option>Mitjà</option>";
        echo "<option>Alt</option>";
        echo "</select>";
        echo "</td>";
        echo "<td>" . $row["descripcio"] . "</td>";
        echo "<td>" . $row["dataFi"] . "</td>";
        echo "<td>";
        echo "<select name="."tech". ">";
        
        while ($rowTech = $resultTech->fetch_assoc()) {
            echo "  <option value='" . $rowTech["ID"] . "'>" .$rowTech["Nom"] . "</option>" ;
        }
        echo "</select>";
        echo "</td>";
        echo "<td>" . $row["departament"] . "</td>";
        echo "<td>";
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
</div>

    <input class="input-admin" type="submit" value="Guardar">
</form>
</main>
<?php 
    }
    ?>
</body>
<?php include_once "../globals/footer.php";?>
</html>