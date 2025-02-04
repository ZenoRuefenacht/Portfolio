<?php
include '../includes/config.php';
session_start();
include '../views/header.php';

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("❌ Fehler: Keine Projekt-ID angegeben.");
}

$project_id = $_GET["id"];

// Projekt abrufen
$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

if (!$project) {
    die("❌ Fehler: Projekt nicht gefunden.");
}

// Sichtbarkeiten abrufen
$stmt = $pdo->prepare("SELECT access_level FROM project_visibility WHERE project_id = ?");
$stmt->execute([$project_id]);
$visibility = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Berechtigungen prüfen
$role = $_SESSION["role"] ?? "public";
if ($role !== "admin" && !in_array("public", $visibility) && !in_array($role, $visibility)) {
    die("❌ Fehler: Du hast keine Berechtigung, dieses Projekt zu sehen.");
}

// Bilder abrufen
$stmt = $pdo->prepare("SELECT image_url FROM project_images WHERE project_id = ?");
$stmt->execute([$project_id]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($project['title']) ?> - Projekt</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/slider.js" defer></script>
    <style>
        .image-slider {
            position: relative;
            width: 400px;
            height: 250px;
            overflow: hidden;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .image-slider img {
            width: 100%;
            height: auto;
            display: none;
        }
        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
        }
        .slider-controls button {
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h1><?= htmlspecialchars($project['title']) ?></h1>
    <p><strong>Status:</strong> <?= $project['status'] == "completed" ? "Abgeschlossen" : "In Bearbeitung" ?></p>
    <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>

    <?php if ($images): ?>
        <div class="image-slider">
            <?php foreach ($images as $image): ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="Projektbild">
            <?php endforeach; ?>
            <div class="slider-controls">
                <button class="prev">⬅</button>
                <button class="next">➡</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($project["project_link"])): ?>
        <p><a href="<?= htmlspecialchars($project["project_link"]) ?>" target="_blank">🔗 Projekt-Link</a></p>
    <?php endif; ?>

    <a href="projects.php">⬅ Zurück zur Projektübersicht</a>
</body>
</html>
