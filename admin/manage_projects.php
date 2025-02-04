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

// Projekt hinzufügen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_project"])) {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $status = $_POST["status"];
    $visibility = $_POST["visibility"]; // Array von Gruppenberechtigungen
    $media_url = "";

    if (!empty($_FILES["media"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["media"]["name"]);
        if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
            $media_url = $target_file;
        }
    }

    // Projekt speichern
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, status, media_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt->execute([$title, $description, $category, $status, $media_url])) {
        $project_id = $pdo->lastInsertId();

        // Sichtbarkeit speichern
        foreach ($visibility as $role) {
            $stmt = $pdo->prepare("INSERT INTO project_visibility (project_id, access_level) VALUES (?, ?)");
            $stmt->execute([$project_id, $role]);
        }

        $message = "✅ Projekt erfolgreich hinzugefügt!";
    } else {
        $message = "❌ Fehler beim Speichern.";
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

    <h2>➕ Neues Projekt hinzufügen</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Projekttitel" required>
        <textarea name="description" placeholder="Projektbeschreibung" required></textarea>

        <label>Kategorie:</label>
        <select name="category">
            <option value="video">Video</option>
            <option value="design">Design</option>
            <option value="website">Website</option>
            <option value="other">Sonstiges</option>
        </select>

        <label>Status:</label>
        <select name="status">
            <option value="in_progress">In Bearbeitung</option>
            <option value="completed">Abgeschlossen</option>
        </select>

        <label>Sichtbarkeit (Mehrfachauswahl möglich):</label>
        <select name="visibility[]" multiple required>
            <option value="public">Öffentlich</option>
            <option value="client">Nur Kunden</option>
            <option value="recruiter">Nur Recruiter</option>
            <option value="familyandfriends">Nur Familie & Freunde</option>
        </select>

        <label>Medien (optional, Bild/Video):</label>
        <input type="file" name="media" accept="image/*,video/*">

        <button type="submit" name="add_project">Projekt speichern</button>
    </form>

    <h2>📂 Bestehende Projekte</h2>
    <ul>
        <?php foreach ($projects as $project): ?>
            <li style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <h2><?= htmlspecialchars($project['title']) ?></h2>
                <p><?= htmlspecialchars(substr($project['description'], 0, 100)) ?>...</p>

                <?php if (!empty($project["media_url"])): ?>
                    <img src="<?= htmlspecialchars($project["media_url"]) ?>" alt="Projektbild" style="max-width: 200px;">
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
