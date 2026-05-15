<?php require_once '../globals/connexio.php'; ?>
<?php

session_start();

/*

    Array temporal d'usuaris.

    Més endavant això es podria substituir per una base de dades.

*/

$sql = "SELECT * FROM Users";
$result = $conn->query($sql);

$usuaris = array();

while ($row = $result->fetch_assoc()) {
    $usuaris[$row['Nom']] = $row['Password'];
}
$error = "";
/*

    Si ja està autenticat, el redirigim directament

*/

if (isset($_SESSION["usuari"])) {

    header("Location: login_success.php");

    exit();

}

/*

    Quan s'envia el formulari

*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuari = $_POST["usuari"];

    $password = $_POST["password"];

    /*

        Comprovem si l'usuari existeix i si la contrasenya coincideix

    */

    if (isset($usuaris[$usuari]) && $usuaris[$usuari] == $password) {

        $_SESSION["usuari"] = $usuari;

        $sql = "SELECT Rol FROM Users WHERE Nom = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuari);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $_SESSION["rol"] = $row["Rol"];
        }

        header("Location: login_success.php");
        exit();

    } else {

        $error = "Usuari o contrasenya incorrectes";

    }

}

?>
<!DOCTYPE html>

<html lang="ca">

<head>

    <meta charset="UTF-8">
    <link href="../css/login.css" rel="stylesheet">
    <title>Login</title>

</head>

<body>
    <div class="login-container">
        <h1>Inici de sessió</h1>

        <?php

        if ($error != "") {

            echo "<p style='color:red;'>$error</p>";

        }

        ?>

        <form method="POST" action="login.php">

            <label>Usuari:</label>

            <input type="text" name="usuari" required><br>

            <label>Contrasenya:</label>

            <input type="password" name="password" required><br>

            <button type="submit">Entrar</button>

        </form>
    </div>
</body>

</html>