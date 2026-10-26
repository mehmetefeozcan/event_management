<?php
require_once __DIR__ . '/../config/app.php';

$pageTitle = "Haftalık Takvim";
$pagePath = "/calendar/index.php";

$calendar = buildWeeklyCalendar($eventList);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php include_once "../config/head_config.php" ?>
</head>

<body>
    <?php include_once COMPONENTS_PATH . "/layout/header.php" ?>

    <div class="container p-4 bg-light mt-4 mb-5 pb-5 rounded-3">
        <div class="row justify-content-between mt-3">
            <span class="col-auto">
                <h3><?= $pageTitle ?></h3>
            </span>
            <div class="col-auto d-flex gap-2">
                <span class="badge bg-primary-subtle text-primary-emphasis border">Dolu saat</span>
                <span class="badge text-bg-light border">Boş saat</span>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered align-middle text-center mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 90px;">Saat</th>
                        <?php foreach ($calendar['days'] as $dayName): ?>
                            <th scope="col"><?= $dayName ?></th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calendar['hours'] as $hour): ?>
                        <tr>
                            <th scope="row" class="text-nowrap"><?= sprintf('%02d.00', $hour) ?></th>
                            <?php foreach ($calendar['days'] as $dayNo => $dayName):
                                $cell = $calendar['cells'][$hour][$dayNo] ?? [];
                            ?>
                                <td class="text-start small <?= empty($cell) ? 'bg-light' : 'bg-primary-subtle' ?>">
                                    <?php foreach ($cell as $event): ?>
                                        <a href="<?= BASE_PATH ?>/event/detail.php?id=<?= $event['id'] ?>"
                                            class="d-block text-decoration-none text-primary-emphasis mb-1">
                                            <?= htmlspecialchars($event['name']) ?>
                                        </a>
                                    <?php endforeach ?>
                                </td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($calendar['outside'])): ?>
            <div class="mt-4">
                <p class="small text-muted mb-2">Takvim aralığı (09.00–18.00) dışında kalan etkinlikler:</p>
                <ul class="list-group list-group-flush">
                    <?php foreach ($calendar['outside'] as $event): ?>
                        <li class="list-group-item px-0"><?= htmlspecialchars($event['name']) ?> · <?= formatEventTime($event) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "../config/script_config.php" ?>
</body>

</html>
