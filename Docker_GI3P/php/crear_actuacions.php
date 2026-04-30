<?php include_once "header.php"; ?>
<?php require_once 'connexio.php'; ?>

<?php

if (isset($_GET['id'])) {
    $id_incidencia = $_GET['id'];
}
    ?>

<body>
    <h1>Actuacions</h1>
    <?php $sql = "SELECT * FROM incidencia where id = $id_incidencia";
    $result = $conn->query($sql);

    $cont = "SELECT COUNT(*) as total FROM actuacions WHERE incidencia = $id_incidencia";
    $res_cont = $conn->query($cont);
    $row_cont = $res_cont->fetch_assoc();
    $total = $row_cont['total'];

    ?>
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
            <th style="border: 1px solid black;">
                Actuacións
            </th>
        </tr>
        <?php
       while ($row = $result->fetch_assoc()) {
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
    <h4>Crear nova actuació</h4>















</body>
</html>