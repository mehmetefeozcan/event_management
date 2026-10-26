<?php
require_once __DIR__ . '/../config/app.php';

$pageTitle = "Rezervasyonlarım";
$pagePath = "/reservation/index.php";

$rows = [];

foreach ($_SESSION['reservations'] as $eventId => $studentIds) {
    $event = findById($eventList, $eventId);

    foreach ($studentIds as $studentId) {
        $student = findById($studentList, $studentId);

        if ($event !== null && $student !== null) {
            $rows[] = ['event' => $event, 'student' => $student];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <?php include_once "../config/head_config.php" ?>
</head>

<body>
    <?php include_once COMPONENTS_PATH . "/layout/header.php" ?>

    <div class="container p-4 bg-light mt-4 mb-5 pb-5 rounded-3">
        <?php include_once COMPONENTS_PATH . "/layout/flash.php" ?>

        <div class="row justify-content-between mt-3">
            <span class="col-auto">
                <h3><?= $pageTitle ?></h3>
            </span>
            <div class="col-auto">
                <a class="btn btn-primary btn-sm rounded-2" href="<?= BASE_PATH ?>/index.php" role="button">Etkinliklere git</a>
            </div>
        </div>

        <?php if (empty($rows)): ?>
            <p class="text-muted mt-4">Henüz rezervasyon yok.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover mt-4">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Öğrenci</th>
                            <th scope="col">Etkinlik</th>
                            <th scope="col">Tarih ve Saat</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $index => $row): ?>
                            <tr>
                                <td class="align-middle"><?= $index + 1 ?></td>
                                <td class="align-middle"><?= htmlspecialchars($row['student']['name']) ?></td>
                                <td class="align-middle">
                                    <a href="<?= BASE_PATH ?>/event/detail.php?id=<?= $row['event']['id'] ?>" class="text-black">
                                        <?= htmlspecialchars($row['event']['name']) ?>
                                    </a>
                                </td>
                                <td class="align-middle"><?= formatEventTime($row['event']) ?></td>
                                <td class="align-middle">
                                    <form action="store.php" method="POST"
                                        onsubmit="return confirm('Bu rezervasyonu iptal etmek istediğinize emin misiniz?');">
                                        <input type="hidden" name="action" value="cancel">
                                        <input type="hidden" name="event_id" value="<?= $row['event']['id'] ?>">
                                        <input type="hidden" name="student_id" value="<?= $row['student']['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">İptal et</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "../config/script_config.php" ?>
</body>

</html>
