<?php
include '../includes/auth.php';
include '../includes/config.php';
check_auth(['admin']);

$message = "";

// Projekt löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_project"])) {
    $project_id = $_POST["project_id"];
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    if ($stmt->execute([$project_id])) {
        $message = "✅ Projekt erfolgreich gelöscht.";
    } else {
        $message = "❌ Fehler beim Löschen des Projekts.";
    }
}

// Alle Projekte abrufen
$stmt = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC");
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Projektverwaltung</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/navigation.php'; ?>

    <h1>Projektverwaltung</h1>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <a href="add_project.php">➕ Neues Projekt hinzufügen</a>

    <h2>📂 Bestehende Projekte</h2>
    <ul>
        <?php foreach ($projects as $project): ?>
            <li style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h2><?= htmlspecialchars($project['title']) ?></h2>
                <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>

                <?php
                // Sichtbarkeiten abrufen
                $stmt = $pdo->prepare("SELECT access_level FROM project_visibility WHERE project_id = ?");
                $stmt->execute([$project['id']]);
                $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Bilder abrufen
                $stmt = $pdo->prepare("SELECT id, image_url FROM project_images WHERE project_id = ?");
                $stmt->execute([$project['id']]);
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <p><strong>Sichtbar für:</strong> <?= implode(", ", $roles) ?></p>

                <!-- Bildvorschau -->
                <?php foreach ($images as $image): ?>
                    <div style="display: inline-block; margin-right: 10px;">
                        <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Projektbild" style="width: 100px;">
                    </div>
                <?php endforeach; ?>

                <?php if (!empty($project["project_link"])): ?>
                    <p><a href="<?= htmlspecialchars($project["project_link"]) ?>" target="_blank">🔗 Projekt-Link</a></p>
                <?php endif; ?>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="project_id" value="<?= $project['id'] ?>">
                    <button type="submit" name="delete_project" style="background-color: red; color: white;" onclick="return confirm('Möchtest du dieses Projekt wirklich löschen?')">Löschen</button>
                </form>
                <a href="edit_project.php?id=<?= $project['id'] ?>" style="margin-left: 10px;">📝 Bearbeiten</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php">⬅ Zurück zum Admin-Dashboard</a>
</body>
</html>
