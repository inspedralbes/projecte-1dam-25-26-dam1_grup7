<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>

<header>
    <a href="admin.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Veure Incidències</h1>
</header>
<hr>

<body class="page-admin">

    <?php $sql = "SELECT * FROM incidencia";
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
            echo "<tr onclick=\"window.location='veure_actuacions_admin.php?id=" . $row["id"] . "';\" style='cursor: pointer;'>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["dataInici"] . "</td>";
            echo "<td><span class='$status'>{$row['prioritat']}</span></td>";
            echo "<td>" . $row["descripcio"] . "</td>";
            echo "<td>" . $row["dataFi"] . "</td>";
            echo "<td>" . $row["tecnic"] . "</td>";
            echo "<td>" . $row["departament"] . "</td>";
            echo "<td>" . $row["tipologia"] . "</td>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
    <?php
    function crear_incidencia($conn)
    {
    }
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>