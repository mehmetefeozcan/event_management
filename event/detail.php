<?php
require_once __DIR__ . '/../config/app.php';

$pageTitle = "Etkinlik Detayı";
$pagePath = "/index.php";

$eventId = (int) ($_GET['id'] ?? 0);
$event = findEvent($eventList, $eventId);

$reservations = $_SESSION['reservations'];
$waitlist = $_SESSION['waitlist'];

if ($event === null) {
    http_response_code(404);
} else {
    $remaining = remainingCapacity($event, $reservations);
    $count = reservationCount($reservations, $eventId);
    $rate = occupancyRate($event, $reservations);
    $roundedRate = round($rate);
    $waitingCount = count($waitlist[$eventId] ?? []);

    $registeredStudents = [];
    foreach ($reservations[$eventId] ?? [] as $studentId) {
        $student = findStudent($studentList, $studentId);
        if ($student !== null) {
            $registeredStudents[] = $student;
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
        <?php if ($event === null): ?>
            <h3>Etkinlik bulunamadı</h3>
            <p class="text-muted">Aradığınız etkinlik mevcut değil.</p>
            <a class="btn btn-primary btn-sm rounded-2" href="<?= BASE_PATH ?>/index.php">Etkinliklere dön</a>
        <?php else: ?>
            <?php include_once COMPONENTS_PATH . "/layout/flash.php" ?>

            <h3 class="mt-3"><?= htmlspecialchars($event['name']) ?></h3>
            <p class="text-muted"><?= htmlspecialchars($event['description']) ?></p>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="bg-white rounded-4 px-3 py-2 border"><?= formatEventTime($event) ?></span>
                <span class="bg-white rounded-4 px-3 py-2 border">Süre: <?= $event['duration'] ?> saat</span>
                <span class="bg-white rounded-4 px-3 py-2 border">Kontenjan: <?= $count ?> / <?= $event['capacity'] ?></span>
                <span class="bg-white rounded-4 px-3 py-2 border">Min. Sınıf: <?= $event['min_grade'] ?></span>
            </div>

            <div class="progress mb-2" role="progressbar" aria-label="Doluluk"
                aria-valuenow="<?= $roundedRate ?>" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-<?= occupancyColor($rate) ?>" style="width: <?= $roundedRate ?>%"></div>
            </div>
            <p class="small text-muted mb-4">
                Doluluk: %<?= $roundedRate ?> · <?= occupancyClass($rate) ?> · Bekleme listesi: <?= $waitingCount ?> öğrenci
            </p>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="bg-white rounded-4 border p-4 h-100">
                        <h5>Öğrenci seçerek kayıt</h5>
                        <form method="POST"
                            action="<?= BASE_PATH ?>/<?= $remaining > 0 ? 'reservation/store.php' : 'waitlist/store.php' ?>">
                            <input type="hidden" name="action" value="<?= $remaining > 0 ? 'create' : 'add' ?>">
                            <input type="hidden" name="event_id" value="<?= $eventId ?>">

                            <label for="student" class="form-label">Öğrenci</label>
                            <select id="student" name="student_id" class="form-select mb-3" required>
                                <option value="">Seçiniz</option>
                                <?php foreach ($studentList as $student): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= htmlspecialchars($student['name']) ?> (<?= $student['grade'] ?>. sınıf)
                                    </option>
                                <?php endforeach ?>
                            </select>

                            <?php if ($remaining > 0): ?>
                                <button type="submit" class="btn btn-primary w-100">Kayıt ol</button>
                            <?php else: ?>
                                <p class="small text-muted">Kontenjan dolu. Bekleme listesine başvurabilirsiniz.</p>
                                <button type="submit" class="btn btn-warning w-100">Bekleme listesine ekle</button>
                            <?php endif ?>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="bg-white rounded-4 border p-4 h-100">
                        <h5>Kayıtlı öğrenciler</h5>
                        <?php if (empty($registeredStudents)): ?>
                            <p class="text-muted mb-0">Henüz kayıt yok.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($registeredStudents as $student): ?>
                                    <li class="list-group-item px-0">
                                        <?= htmlspecialchars($student['name']) ?>
                                        <span class="text-muted">(<?= $student['grade'] ?>. sınıf)</span>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?php include_once COMPONENTS_PATH . "/layout/footer.php" ?>

    <?php include_once "../config/script_config.php" ?>
</body>

</html>
