<?php include_once "header.php";?>
<html>
<body class="page-admin">
    <header>
        <a href="javascript:history.back()" class="btn-back">
            <span class="arrow">←</span> Tornar
        </a>
        <h1>Gestor d'incidències per a Administradors</h1>
    </header>
        <hr>
    <main>
        <div class="menu">
            <a href="informacio_tecnics.php"><input type="button" class="btn-inf-tecn" value="Informació de Tècnics"></a>
            <a href="assignar_incidencia.php"><input type="button" class="btn-assign-inc" value="Assignar Incidències"></a>
            <a href="estadistiques_acces.php"><input type="button" class="btn-est-accs" value="Estadistiques d'Accés"></a>
        </div>
    </main>
</body>
<?php include_once "footer.php";?>
</html>