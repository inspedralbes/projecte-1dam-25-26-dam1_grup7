<?php ob_start(); ?>
<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php');?>

<?php
if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
?>

<header>
    <a href="actuacio_tecnic.php" class="btn-back">
        <span class="arrow">←</span> Tornar
    </a>
    <h1>Crear Actuació</h1>
</header>
<hr>

<body>
    <?php
    $sql = "SELECT * FROM incidencia where id = $id_incidencia";
    $result = $conn->query($sql);

    $cont = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
    $res_cont = $conn->query($cont);
    $row_cont = $res_cont->fetch_assoc();
    $total = $row_cont['total'];

    $incidencia_finalitzada = false;
    ?>
    <div class="table-container">
    <table class="modern-table">
    <thead>
        <tr style="background-color: var(--tech-main);">
            <th>ID</th>
            <th>Data Inici</th>
            <th>Prioritat</th>
            <th>Descripció</th>
            <th>Data Fi</th>
            <th>Tecnic Assignat</th>
            <th>Departament</th>
            <th>Tipologia</th>
            <th>Actuacións</th>
        </tr>
</thead>
        <?php
        while ($row = $result->fetch_assoc()) {
            
            if (!empty($row["dataFi"])) {
                $incidencia_finalitzada = true;
            }
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

    <?php
    function crear_actuacions($conn, $id_incidencia)
    {
        // Obtenir dades del formulari
        $temps = $_POST['temps'];
        $descripcio = $_POST['desc'];
        $visible = $_POST['visible'];
        $finalitzat = $_POST['final'];

        if (empty($temps)) {
            echo "<p class='error'>El Temps no pot estar buit.</p>";
            return;
        }

        if (empty($descripcio)) {
            echo "<p class='error'>La Descripció no pot estar buida.</p>";
            return;
        }
        $sql = "INSERT INTO actuacions (dataActuacio, descActuacio, visible, temps, incidencia) VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siii", $descripcio, $visible, $temps, $id_incidencia);

        // Executar la consulta i comprovar errors
        if ($stmt->execute()) {
            echo "<p class='success'>Actuació creada correctament.</p>";
        }
        else{
            echo "<p class='error'>Error al crear l'actuació: " . htmlspecialchars($stmt->error) . "</p>";
        }
        registrarLog();
        
        if ($finalitzat == "1") {
            $sql = "UPDATE incidencia SET dataFI = NOW() WHERE id = ?";
            $stmt_upd = $conn->prepare($sql);
            $stmt_upd->bind_param("i", $id_incidencia);
            $stmt_upd->execute();
            $stmt_upd->close();

            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_incidencia . "&status=completat");
            exit();
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_incidencia);
        exit();

        $stmt->close();
    }

    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        crear_actuacions($conn, $id_incidencia);
    }

    if ($incidencia_finalitzada) {
        echo "<h2 style='text-align: center;'>Incidència finalitzada</h2>";
    } else {
        ?>
        <main>

            <form method="POST" action="crear_actuacions.php?id=<?php echo $id_incidencia; ?>">
                <fieldset>
                    <h3 style="text-align: center;">Crear nova actuació</h4>
                        <label for="desc">Descripció:</label>
                        <input type="text" id="desc" name="desc">
                        <br>
                        <label for="temps">Temps total per l'actuació:</label>
                        <input type="number" id="temps" name="temps">
                        <br>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="hidden" name="visible" value="0">
                                <input type="checkbox" id="visible" name="visible" value="1">
                                <label for="visible">Visible per l'Usuari</label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="final" value="0">
                                <input type="checkbox" id="final" name="final" value="1">
                                <label for="final">Finalitzada</label>
                            </div>
                        </div>
                        <input class="input-tech" type="submit" value="Crear">
                </fieldset>
            </form>
        </main>
        <?php
    }
    ?>

    <?php
    $sql = "SELECT * FROM actuacions where incidencia = $id_incidencia ORDER BY dataActuacio ";
    $result = $conn->query($sql);
    ?>
    <div class="table-container">
    <table class="modern-table">
     <thead>
        <tr style="background-color: var(--tech-main);">
            <th>ID Actuacio</th>
            <th>Data Actuacio</th>
            <th>Descripció</th>
            <th>Visible</th>
            <th>Temps total</th>
            <th>ID</th>
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