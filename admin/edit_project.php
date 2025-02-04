<?php
include '../includes/auth.php';
include '../includes/config.php';
check_auth(['admin']);

$message = "";

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

// Bilder abrufen
$stmt = $pdo->prepare("SELECT id, image_url FROM project_images WHERE project_id = ?");
$stmt->execute([$project_id]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Falls das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $project_link = $_POST["project_link"] ?? "";
    $new_visibility = $_POST["visibility"] ?? [];

    // Projekt aktualisieren
    $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, status = ?, project_link = ? WHERE id = ?");
    if ($stmt->execute([$title, $description, $status, $project_link, $project_id])) {
        // Sichtbarkeiten aktualisieren
        $stmt = $pdo->prepare("DELETE FROM project_visibility WHERE project_id = ?");
        $stmt->execute([$project_id]);
        foreach ($new_visibility as $role) {
            $stmt = $pdo->prepare("INSERT INTO project_visibility (project_id, access_level) VALUES (?, ?)");
            $stmt->execute([$project_id, $role]);
        }

        $message = "✅ Projekt erfolgreich aktualisiert!";
    } else {
        $message = "❌ Fehler beim Aktualisieren.";
    }

    // Falls neue Bilder hochgeladen werden
    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        if (!empty($_FILES["images"]["name"][$key])) {
            $target_dir = "../uploads/";
            $file_name = basename($_FILES["images"]["name"][$key]);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $stmt = $pdo->prepare("INSERT INTO project_images (project_id, image_url) VALUES (?, ?)");
                $stmt->execute([$project_id, $target_file]);
            }
        }
    }
}

// Bild löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_image"])) {
    $image_id = $_POST["image_id"];
    $stmt = $pdo->prepare("SELECT image_url FROM project_images WHERE id = ?");
    $stmt->execute([$image_id]);
    $image = $stmt->fetch();

    if ($image) {
        unlink($image["image_url"]); // Datei vom Server löschen
        $stmt = $pdo->prepare("DELETE FROM project_images WHERE id = ?");
        $stmt->execute([$image_id]);
        header("Location: edit_project.php?id=$project_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Projekt bearbeiten</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/navigation.php'; ?>
    <h1>Projekt bearbeiten</h1>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
        <textarea name="description" required><?= htmlspecialchars($project['description']) ?></textarea>

        <label>Status:</label>
        <select name="status">
            <option value="in_progress" <?= $project['status'] == 'in_progress' ? 'selected' : '' ?>>In Bearbeitung</option>
            <option value="completed" <?= $project['status'] == 'completed' ? 'selected' : '' ?>>Abgeschlossen</option>
        </select>

        <label>Projekt-Link (optional):</label>
        <input type="url" name="project_link" value="<?= htmlspecialchars($project['project_link']) ?>" placeholder="Projekt-Link">

        <label>Sichtbarkeit:</label>
        <div>
            <input type="checkbox" name="visibility[]" value="public" <?= in_array('public', $visibility) ? 'checked' : '' ?>> Öffentlich<br>
            <input type="checkbox" name="visibility[]" value="client" <?= in_array('client', $visibility) ? 'checked' : '' ?>> Nur Kunden<br>
            <input type="checkbox" name="visibility[]" value="recruiter" <?= in_array('recruiter', $visibility) ? 'checked' : '' ?>> Nur Recruiter<br>
            <input type="checkbox" name="visibility[]" value="familyandfriends" <?= in_array('familyandfriends', $visibility) ? 'checked' : '' ?>> Nur Familie & Freunde<br>
        </div>

        <label>Neue Bilder hinzufügen:</label>
        <input type="file" name="images[]" multiple accept="image/*">

        <button type="submit">Speichern</button>
    </form>

    <h2>Aktuelle Bilder:</h2>
    <ul>
        <?php foreach ($images as $image): ?>
            <li>
                <img src="<?= htmlspecialchars($image['image_url']) ?>" style="width: 100px;">
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                    <button type="submit" name="delete_image">🗑️ Löschen</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="manage_projects.php">⬅ Zurück</a>
</body>
</html>
