<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>
<html>
<header>
    <a href="admin.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Assignar una Incidencia</h1>
</header>
<hr>

<body class="page-admin">
    <?php

    
    // Consulta SQL per obtenir totes les files de la taula 'cases'
    
    function assignar_incidencia($conn)
    {
        $tecnic = $_POST['tech'];
        $priotitat = $_POST['prio'];

        $sql = "INSERT INTO incidencia (id, prioritat, tecnic) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);  //La variable $conn la tenim per haver inclòs el fitxer connexio.php
        $stmt->bind_param("ss", $tecnic, $priotitat);

        if ($stmt->execute()) {
            echo "<p class='info'>Incidencia assignada amb èxit!</p>";
        } else {
            echo "<p class='error'>Error al assignar l'incidencia: " . htmlspecialchars($stmt->error) . "</p>";
        }
    }

    ?>
    <?php $sql = "SELECT * FROM incidencia where dataFI IS NULL";
    $result = $conn->query($sql); ?>
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
            if ($row["prioritat"] == "Baix") {
                $status = "stats baixa"; 
            } elseif ($row["prioritat"] == "Mitjà") {
                $status = "stats mitjana"; 
            } elseif ($row["prioritat"] == "Alt") {
                $status = "stats alta"; 
            } else {
                $status = "white"; 
            }
            echo "<tr onclick=\"window.location='modificar_incidencia.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["dataInici"] . "</td>";
            echo "<td><span class='$status'>{$row['prioritat']}</span></td>";
            echo "<td>" . $row["descripcio"] . "</td>";
            echo "<td>" . $row["dataFi"] . "</td>";
            echo "<td>" . $row["tecnic"] . "</td>";
            echo "<td>" . $row["departament"] . "</td>";
            echo "<td>" . $row["tipologia"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>