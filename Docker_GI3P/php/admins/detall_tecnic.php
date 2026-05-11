<?php include_once "../globals/header.php"; ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/logger.php'); ?>
<?php
require_once '../globals/connexio.php';

$nomTecnic = urldecode($_GET['nom']);

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Detall: <?php echo $nomTecnic; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<header>
    <a href="informacio_tecnics.php" class="btn-back">
        <span class="arrow">←</span> Tornar al llistat
    </a>
    <h1>Detall del tècnic: <?php echo $nomTecnic; ?></h1>
</header>
<hr>

<body class="page-users">
    <?php
    $sql = "SELECT COUNT(i.dataFi) AS resoltes, (COUNT(i.id) - COUNT(i.dataFi)) AS en_proces
            FROM incidencia i JOIN tecnic t ON i.tecnic = t.idTecnic WHERE t.nom = '$nomTecnic'";

    $resultat = $conn->query($sql);
    $row = $resultat->fetch_assoc();

    $resoltes = $row['resoltes'] ?? 0;
    $enProces = $row['en_proces'] ?? 0;

    if ($resoltes == 0 && $enProces == 0) {
        echo "<p>No s'han trobat dades per a aquest tècnic.</p>";
    } else {
    ?>
        <div style="width: 400px; margin: 30px auto; text-align: center;">
            <h3>Estat de les Incidències</h3>
            <canvas id="chartDetall"></canvas>
            
            <div style="margin-top: 20px; text-align: left; display: inline-block;">
                <p><strong>Resoltes:</strong> <?php echo $resoltes; ?></p>
                <p><strong>En procés:</strong> <?php echo $enProces; ?></p>
                <p><strong>Total:</strong> <?php echo ($resoltes + $enProces); ?></p>
            </div>
        </div>

        <script>
            new Chart(document.getElementById('chartDetall'), {
                type: 'bar',
                data: {
                    labels: ['Resoltes', 'En Procés'],
                    datasets: [{
                        data: [<?php echo $resoltes; ?>, <?php echo $enProces; ?>],
                        backgroundColor: ['#3dd600', '#c50000']
                    }]
                }
            });
        </script>
    <?php
    }
    registrarLog();
    ?>
</body>
<?php include_once "../globals/footer.php"; ?>
</html>