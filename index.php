<?php
require_once __DIR__ . '/config/app.php';

$pageTitle = "Hoşgeldiniz";
$pagePath = "/index.php";

$reservations = $_SESSION['reservations'];
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php include_once "./config/head_config.php" ?>
</head>

<body>
    <?php include_once COMPONENTS_PATH . "/layout/header.php" ?>

    <div class="container mt-4 mb-5 pb-5 d-grid gap-4">
        <h1>Hoşgeldiniz</h1>
        <h5>Bu haftanın Etkinliklerine göz atın</h5>
        <?php foreach ($eventList as $event):
            $count = reservationCount($reservations, $event['id']);
            $rate = occupancyRate($event, $reservations);
            $roundedRate = round($rate);
        ?>
            <div class="position-relative rounded-4 p-4 bg-light">
                <h3><?= htmlspecialchars($event['name']) ?></h3>
                <p><?= htmlspecialchars($event['description']) ?></p>
                <div class="d-flex justify-content-start gap-4">
                    <span class="bg-white rounded-4 px-3 py-2 border">
                        Kontenjan: <?= $count ?> / <?= $event['capacity'] ?>
                    </span>
                    <span class="bg-white rounded-4 px-3 py-2 border">
                        Min. Sınıf: <?= $event['min_grade'] ?>
                    </span>
                </div>
                <div class="progress mt-4" role="progressbar" aria-label="Doluluk"
                    aria-valuenow="<?= $roundedRate ?>" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-<?= occupancyColor($rate) ?>" style="width: <?= $roundedRate ?>%"></div>
                </div>
                <span class="d-inline-block px-3 py-2 mt-3">
                    <?= formatEventTime($event) ?>
                </span>

                <a href="<?= BASE_PATH ?>/event/detail.php?id=<?= $event['id'] ?>"
                    class="btn btn-warning position-absolute bottom-0 end-0 m-4">Detaya git</a>
            </div>
        <?php endforeach ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "./config/script_config.php" ?>
</body>

</html>
