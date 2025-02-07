<?php
include '../includes/config.php';
session_start();
include '../views/header.php'; 

$role = $_SESSION["role"] ?? "public";

if ($role === "admin") {
    $sql = "SELECT DISTINCT p.* FROM projects p";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT DISTINCT p.* FROM projects p
            LEFT JOIN project_visibility pv ON p.id = pv.project_id
            WHERE pv.access_level = 'public' OR pv.access_level = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role]);
}

$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Projekte</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .image-slider {
            position: relative;
            width: 300px;
            height: 200px;
            overflow: hidden;
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

    <h1>Meine Projekte</h1>
    <ul>
        <?php if (empty($projects)): ?>
            <li>Keine Projekte verfügbar.</li>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
                <li style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <h2><?= htmlspecialchars($project['title']) ?></h2>
                    <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>

                    <?php
                    $stmt = $pdo->prepare("SELECT access_level FROM project_visibility WHERE project_id = ?");
                    $stmt->execute([$project['id']]);
                    $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    ?>

                    <?php if ($role !== "admin" && in_array("private", $roles)): ?>
                        <!-- Private Projekte sind für andere nicht sichtbar -->
                        <?php continue; ?>
                    <?php endif; ?>

                    <p><strong>Sichtbar für:</strong> <?= implode(", ", $roles) ?></p>

                    <a href="project_detail.php?id=<?= $project['id'] ?>">🔍 Mehr erfahren</a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <a href="../index.php">⬅ Zurück zur Startseite</a>
</body>
</html>
