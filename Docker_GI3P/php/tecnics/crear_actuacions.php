<?php ob_start(); ?>
<?php include_once "../globals/header.php"; ?>
<?php require_once '../globals/connexio.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
?>

<header>
    <a href="javascript:history.back()" class="btn-back">
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
    <table>
        <tr style="border: 1px solid black;">
            <th style="border: 1px solid black;">ID</th>
            <th style="border: 1px solid black;">Data Inici</th>
            <th style="border: 1px solid black;">Prioritat</th>
            <th style="border: 1px solid black;">Descripció</th>
            <th style="border: 1px solid black;">Data Fi</th>
            <th style="border: 1px solid black;">Tecnic Assignat</th>
            <th style="border: 1px solid black;">Departament</th>
            <th style="border: 1px solid black;">Tipologia</th>
            <th style="border: 1px solid black;">Actuacións</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            //Si la dataFi no està buida, vol dir que la incidència està finalitzada
            if (!empty($row["dataFi"])) {
                $incidencia_finalitzada = true;
            }

            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black;'>" . $row["id"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataInici"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["prioritat"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["descripcio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataFi"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tecnic"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["departament"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["tipologia"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $total . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
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

        // Preparar la consulta SQL per inserir una nova actuació
        $sql = "INSERT INTO actuacions (dataActuacio, descActuacio, visible, temps, incidencia) VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);  
        $stmt->bind_param("siii", $descripcio, $visible, $temps, $id_incidencia);

        // Executar la consulta i comprovar errors
        if (!$stmt->execute()) {
            echo "<p class='error'>Error al crear l'actuació: " . htmlspecialchars($stmt->error) . "</p>";
        }

        // Si s'ha marcat com a finalitzada, actualitzem la incidència
        if ($finalitzat == "1") {
            $sql = "UPDATE incidencia SET dataFI = NOW() WHERE id = ?";
            $stmt_upd = $conn->prepare($sql); 
            $stmt_upd->bind_param("i", $id_incidencia);
            $stmt_upd->execute();
            $stmt_upd->close();
            
            //Redirigir a la mateixa pàgina per mostrar el missatge de "Completat"
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_incidencia . "&status=completat");
            exit();
        }
        
        //Redirigir per evitar l'enviament duplicat del formulari
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id_incidencia);
        exit();

        $stmt->close();
    }

    $sql = "SELECT * FROM departament";
    $result = $conn->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        crear_actuacions($conn, $id_incidencia);
    }

    // Amagar o mostrar el formulari segons l'estat de la incidència
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
                    <input type="submit" value="Crear">
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

    <table>
        <tr style="border: 1px solid black;">
            <th style="border: 1px solid black;">ID Actuacio</th>
            <th style="border: 1px solid black;">Data Actuacio</th>
            <th style="border: 1px solid black;">Descripció</th>
            <th style="border: 1px solid black;">Visible</th>
            <th style="border: 1px solid black;">Temps total</th>
            <th style="border: 1px solid black;">ID</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border: 1px solid black;'>";
            echo "<td style='border: 1px solid black;'>" . $row["idActuacio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["dataActuacio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["descActuacio"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["visible"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["temps"] . "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["incidencia"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
<?php include_once "../globals/footer.php"; ?>
</html>