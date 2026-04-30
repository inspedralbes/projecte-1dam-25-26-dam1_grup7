<?php include_once "header.php"; ?>
<?php require_once 'connexio.php'; ?>

<body>
    <h1>Consultar Incidencia</h1>
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
        echo "<td style='border: 1px solid black;'>";
        echo "<a href='veure_actuacions.php?id=" . $row["id"] . "'><button>Incidencies</button></a>"; 
        echo "</td>";
        echo "</tr>";
        }
        ?>
    </table>
    <?php
    function crear_incidencia($conn) {

    }

    ?>
</body>
</html>