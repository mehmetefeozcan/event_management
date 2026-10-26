<?php
include_once dirname(__FILE__) . "/events/store.php";

$pageTitle = "Hoşgeldiniz";
$pagePath = "/index.php";
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
        <?php foreach (array_slice($eventList, 0, 3) as $i): ?>
            <div class="position-relative rounded-4 p-4 bg-light">
                <h3><?= $i['name'] ?> </h3>
                <p><?= $i['description'] ?></p>
                <div class="d-flex justify-content-start gap-4">
                    <span class="bg-white rounded-4 px-3 py-2 border">
                        Kontenjan: 0 / <?= $i['capacity'] ?>
                    </span>
                    <span class="bg-white rounded-4 px-3 py-2 border">
                        Min. Sınıf: <?= $i['min_grade'] ?>
                    </span>
                </div>
                <div class="progress mt-4" role="progressbar" aria-label="Success example" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-success" style="width: 25%"></div>
                </div>
                <span class="d-inline-block  px-3 py-2 mt-3 ">
                    <?php
                    $start = new DateTime($i['date']);
                    $end = (clone $start)->modify("+{$i['duration']} hours");
                    ?>
                    <?= $start->format('d.m.Y • H:i') ?>-<?= $end->format('H:i') ?>
                </span>

                <a href="#" class="btn btn-warning position-absolute bottom-0 end-0 m-4">Detaya git</a>
            </div>
        <?php endforeach ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "./config/script_config.php" ?>
</body>

</html>