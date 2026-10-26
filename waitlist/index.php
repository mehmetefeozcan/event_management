<?php
require_once __DIR__ . '/../config/app.php';

$pageTitle = "Bekleme Listesi";
$pagePath = "/waitlist/index.php";

$waitlist = $_SESSION['waitlist'];
$lists = [];

foreach ($eventList as $event) {
    $studentIds = $waitlist[$event['id']] ?? [];

    if (empty($studentIds)) {
        continue;
    }

    $students = [];
    foreach ($studentIds as $studentId) {
        $student = findStudent($studentList, $studentId);
        if ($student !== null) {
            $students[] = $student;
        }
    }

    $lists[] = ['event' => $event, 'students' => $students];
}

$totalWaiting = totalWaiting($waitlist);
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
                <span class="bg-white rounded-4 px-3 py-2 border">Toplam bekleyen: <?= $totalWaiting ?></span>
            </div>
        </div>

        <?php if (empty($lists)): ?>
            <p class="text-muted mt-4">Bekleme listesinde kimse yok.</p>
        <?php endif ?>

        <?php foreach ($lists as $list): ?>
            <div class="bg-white rounded-4 border p-4 mt-4">
                <h5>
                    <a href="<?= BASE_PATH ?>/event/detail.php?id=<?= $list['event']['id'] ?>" class="text-black">
                        <?= htmlspecialchars($list['event']['name']) ?>
                    </a>
                    <span class="badge text-bg-light border ms-2"><?= count($list['students']) ?> öğrenci</span>
                </h5>

                <ul class="list-group list-group-flush mt-3">
                    <?php foreach ($list['students'] as $position => $student): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center gap-3">
                            <span>
                                <span class="badge text-bg-light border me-2"><?= $position + 1 ?></span>
                                <?= htmlspecialchars($student['name']) ?>
                                <span class="text-muted">(<?= $student['grade'] ?>. sınıf)</span>
                            </span>
                            <form action="store.php" method="POST">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="event_id" value="<?= $list['event']['id'] ?>">
                                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Kaldır</button>
                            </form>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endforeach ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "../config/script_config.php" ?>
</body>

</html>
