<?php
include_once __DIR__ . "/paths_config.php";
$pageTitle ??= "";
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars(!empty($pageTitle) ? $pageTitle . " | BÖTE - HAYEF" : "BÖTE - HAYEF") ?> </title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= PUBLIC_CSS_URL . "/main.css" ?>">