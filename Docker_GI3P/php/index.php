<?php include_once "globals/header.php";?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); registrarLog();?>

<body>
    <header>
        <h1>Gestor d'incidències</h1>
        <button id="logout-btn"><a href="globals/logout.php">Tancar sessió</a></button>
    </header>
    <hr>
    <main>
        <div class="menu">
        
        <a href="users/usuaris.php"><input id="user-btn" type="button" value="Usuaris"></a>
        <a href="tecnics/tecnics.php"><input id="tech-btn" type="button" value="Tècnics"></a>
        <a href="admins/admin.php"><input id="admin-btn" type="button" value="Administradors"></a>
    </div>
    </main>
</body>
<?php include_once "globals/footer.php";?>
</html>