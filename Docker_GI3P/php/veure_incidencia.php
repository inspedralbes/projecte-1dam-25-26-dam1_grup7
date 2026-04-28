<?php include_once "header.php";?>
<?php require_once 'connexio.php';?>
<body>
    <h1>Crear una Incidencia</h1>
    <?php

    //farem un select de la taula departaments i recuperarem una matriu de dades

    // Consulta SQL per obtenir totes les files de la taula 'cases'
    $sql = "SELECT * FROM incidencia";
    $result = $conn->query($sql);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Si el formulari s'ha enviatc (mètode POST), cridem a la funció per crear la casa
        crear_incidencia($conn);
    } else {
        //Mostrem el formulari per crear una nova casa
        //Tanquem el php per poder escriure el codi HTML de forma més còmoda.
        ?>
        <form method="POST" action="veure_incidencia.php">
            <fieldset>
                <legend>Incidencia</legend>
                 
                <table>
                    <tr>
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
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["dataInici"] . "</td>";
                        echo "<td>" . $row["prioritat"] . "</td>";
                        echo "<td>" . $row["descripcio"] . "</td>";
                        echo "<td>" . $row["dataFi"] . "</td>";
                        echo "<td>" . $row["tecnic"] . "</td>";
                        echo "<td>" . $row["departament"] . "</td>";
                        echo "<td>" . $row["tipologia"] . "</td>";
                        //echo "  <option value=" . $row["idDept"] . ">" . $row["nom"] . "</option>" ;
                        echo "</tr>";
                        }
                    ?>


                </table>
            </fieldset>
        </form>


        <?php
        //Tanquem l'else
    }
    ?>
</body>

</html>

