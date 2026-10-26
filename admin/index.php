<?php
require_once __DIR__ . '/../config/app.php';

$pageTitle = "Yönetici Paneli";
$pagePath = "/admin/index.php";

$reservations = $_SESSION['reservations'];
$waitlist = $_SESSION['waitlist'];

$analytics = buildEventAnalytics($eventList, $reservations, $waitlist);
$totalCount = countAll($reservations);
$totalWaitingCount = countAll($waitlist);
$mostDemandedEvent = mostDemanded($analytics);
$leastDemandedEvent = leastDemanded($analytics);
$classCounts = occupancyClassCounts($analytics);
$distribution = gradeDistribution($studentList, $reservations);

$gradeLevels = array_unique(array_column($studentList, 'grade'));
sort($gradeLevels);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php include_once "../config/head_config.php" ?>
</head>

<body>
    <?php include_once COMPONENTS_PATH . "/layout/header.php" ?>

    <div class="container p-4 bg-light mt-4 mb-5 pb-5 rounded-3">
        <h3 class="mt-3"><?= $pageTitle ?></h3>

        <div class="row row-cols-2 row-cols-md-4 g-3 mt-2">
            <div class="col">
                <div class="bg-white border rounded-4 p-3 h-100">
                    <div class="text-muted small">Toplam rezervasyon</div>
                    <div class="fs-4 fw-bold"><?= $totalCount ?></div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border rounded-4 p-3 h-100">
                    <div class="text-muted small">Bekleme listesi</div>
                    <div class="fs-4 fw-bold"><?= $totalWaitingCount ?></div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border rounded-4 p-3 h-100">
                    <div class="text-muted small">En çok talep gören</div>
                    <div class="fw-bold">
                        <?= $mostDemandedEvent ? htmlspecialchars($mostDemandedEvent['name']) : '-' ?>
                    </div>
                    <div class="small text-muted">
                        <?= $mostDemandedEvent ? $mostDemandedEvent['count'] . ' kayıt' : '' ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border rounded-4 p-3 h-100">
                    <div class="text-muted small">En az talep gören</div>
                    <div class="fw-bold">
                        <?= $leastDemandedEvent ? htmlspecialchars($leastDemandedEvent['name']) : '-' ?>
                    </div>
                    <div class="small text-muted">
                        <?= $leastDemandedEvent ? $leastDemandedEvent['count'] . ' kayıt' : '' ?>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="mt-5">Etkinlik bazında doluluk</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover mt-3 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Etkinlik</th>
                        <th scope="col">Kayıt / Kontenjan</th>
                        <th scope="col" style="min-width: 180px;">Doluluk</th>
                        <th scope="col">Bekleme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($analytics as $row):
                        $rate = $row['rate'];
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= $row['count'] ?> / <?= $row['capacity'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;" role="progressbar"
                                        aria-label="Doluluk" aria-valuenow="<?= round($rate) ?>" aria-valuemin="0"
                                        aria-valuemax="100">
                                        <div class="progress-bar bg-<?= occupancyColor($rate) ?>"
                                            style="width: <?= round($rate) ?>%"></div>
                                    </div>
                                    <span class="small">%<?= round($rate) ?></span>
                                </div>
                            </td>
                            <td><?= $row['waiting'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="bg-white border rounded-4 p-4 h-100">
                    <h5>Doluluk sınıfları</h5>
                    <ul class="list-group list-group-flush mt-3">
                        <?php foreach ($classCounts as $className => $total): ?>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span><?= $className ?></span>
                                <span class="fw-bold"><?= $total ?> etkinlik</span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="bg-white border rounded-4 p-4 h-100">
                    <h5>Sınıf düzeyine göre katılım</h5>
                    <ul class="list-group list-group-flush mt-3">
                        <?php foreach ($gradeLevels as $grade): ?>
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <span><?= $grade ?>. sınıf</span>
                                <span class="fw-bold"><?= $distribution[$grade] ?? 0 ?> kayıt</span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <?php include_once "../config/script_config.php" ?>
</body>

</html>