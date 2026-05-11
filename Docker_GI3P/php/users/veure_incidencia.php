<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>

<header>
    <a href="usuaris.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Veure Incidències</h1>
</header>
<hr>

<body class="page-users">

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
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            if ($row["prioritat"] == "Baix") {
                $color = "var(--baix-color)"; 
            } elseif ($row["prioritat"] == "Mitjà") {
                $color = "var(--mitja-color)"; 
            } elseif ($row["prioritat"] == "Alt") {
                $color = "var(--alt-color)"; 
            } else {
                $color = "white"; 
            }
            echo "<tr onclick=\"window.location='veure_actuacions.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
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
    <?php
    function crear_incidencia($conn)
    {
    }
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>