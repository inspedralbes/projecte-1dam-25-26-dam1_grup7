<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>

<?php

if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
?>

<body class="page-admin">
    <header>
        <a href="veure_incidencia_admin.php?id=<?php echo $id_incidencia; ?>" class="btn-back">
            <span class="arrow">←</span> Tornar
        </a>
        <h1>Actuacions</h1>
    </header>
    <hr>


    <?php $sql = "SELECT * FROM incidencia where id = $id_incidencia";
    $result = $conn->query($sql);

    $cont = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
    $res_cont = $conn->query($cont);
    $row_cont = $res_cont->fetch_assoc();
    $total = $row_cont['total'];

    ?>
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
            <th>
                Actuacións
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
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["dataInici"] . "</td>";
            echo "<td><span class='$status'>{$row['prioritat']}</span></td>";
            echo "<td>" . $row["descripcio"] . "</td>";
            echo "<td>" . $row["dataFi"] . "</td>";
            echo "<td>" . $row["tecnic"] . "</td>";
            echo "<td>" . $row["departament"] . "</td>";
            echo "<td>" . $row["tipologia"] . "</td>";
            echo "<td>" . $total . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
    <hr>
    <?php $sql = "SELECT * FROM actuacions where incidencia = $id_incidencia ORDER BY dataActuacio ";
    $result = $conn->query($sql); ?>

    <div class="table-container">
    <table class="modern-table">
     <thead>        
        <tr style="background-color: var(--admin-main);">
            <th>
                ID Actuacio
            </th>
            <th>
                Data Actuacio
            </th>
            <th>
                Descripció
            </th>
            <th>
                Visible
            </th>
            <th>
                Temps total
            </th>
            <th>
                ID
            </th>
        </tr>
</thead>
        <?php
        $numIncidencia = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $numIncidencia . "</td>";
            echo "<td>" . $row["dataActuacio"] . "</td>";
            echo "<td>" . $row["descActuacio"] . "</td>";
            echo "<td>" . $row["visible"] . "</td>";
            echo "<td>" . $row["temps"] . "</td>";
            echo "<td>" . $row["incidencia"] . "</td>";
            echo "</tr>";
            $numIncidencia++;
        }
        ?>
    </table>
</div>
</body>
<?php include_once "../globals/footer.php"; ?>

</html>