<?php include_once "../globals/header.php";?>
<html>
<body class="page-users">
    <header>
        <?php
        if ($_SESSION["rol"] == "admin") {
            echo '<a href="../index.php" class="btn-back">
                <span class="arrow">←</span> Tornar
            </a>';
        }
        ?>
        <button id="logout-btn"><a href="../globals/logout.php">Tancar sessió</a></button>
        <h1>Gestor d'incidències per a Usuaris</h1>
    </header>
        <hr>

    <main>
        <div class="menu">
            <a href="crear_incidencia.php"><input type="button" class="btn-crear" value="Crear Incidències"></a>
            <a href="veure_incidencia.php"><input type="button" class="btn-consultar-inc" value="Comprovar Incidències"></a>
            <a href="consum_departaments.php"><input type="button" class="btn-consultar-dep" value="Consum Departaments"></a>
        </div>
    </main>
</body>
<?php include_once "../globals/footer.php";?>
</html>