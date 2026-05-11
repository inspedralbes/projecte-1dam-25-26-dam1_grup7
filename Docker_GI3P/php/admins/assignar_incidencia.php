<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
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
            if ($row["prioritat"] == "Baix") {
                $color = "var(--baix-color)"; 
            } elseif ($row["prioritat"] == "Mitja") {
                $color = "var(--mitja-color)"; 
            } elseif ($row["prioritat"] == "Alt") {
                $color = "var(--alt-color)"; 
            } else {
                $color = "white"; 
            }
            echo "<tr onclick=\"window.location='modificar_incidencia.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
            echo "<td style='border: 1px solid black;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataInici"] . "</td>";
            echo "<td style='border: 1px solid black; background-color: $color;'>" . $row["prioritat"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["descripcio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataFi"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tecnic"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["departament"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tipologia"] . "</td>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>