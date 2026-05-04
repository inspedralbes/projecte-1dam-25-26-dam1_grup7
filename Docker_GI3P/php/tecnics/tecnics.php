<?php include_once "../globals/header.php"; ?>
<html>
<header>
    <a href="javascript:history.back()" class="btn-back">
            <span class="arrow">←</span> Tornar
        </a>
        <h1>Gestor d'incidències per a Tecnics</h1>
</header>
    <hr>

<body class="page-tecnics">
    <main>
        <div class="menu">
            <a href="actuacio_tecnic.php"><input type="button" class="btn-crear-actu" value="Crear Actuació"></a>
            <a href="../globals/consum_departaments.php"><input type="button" class="btn-consultar-dep" value="Consum Departaments"></a>
        </div>
    </main>
</body>
<?php include_once "../globals/footer.php";?>
</html>