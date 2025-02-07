<?php
include '../includes/auth.php';
include '../includes/config.php';
check_auth(['admin']);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $status = $_POST["status"];
    $visibility = $_POST["visibility"] ?? []; // Checkbox-Werte
    $project_link = $_POST["project_link"] ?? ""; 

    // Projekt speichern
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, status, project_link, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt->execute([$title, $description, $category, $status, $project_link])) {
        $project_id = $pdo->lastInsertId();

        // Bilder hochladen
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
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Projekt hinzufügen</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/navigation.php'; ?>

    <h1>Projekt hinzufügen</h1>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

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

        <label>Projekt-Link (optional):</label>
        <input type="url" name="project_link" placeholder="https://beispiel.com">

        <label>Sichtbarkeit:</label>
        <div>
            <input type="checkbox" name="visibility[]" value="public"> Öffentlich<br>
            <input type="checkbox" name="visibility[]" value="client"> Nur Kunden<br>
            <input type="checkbox" name="visibility[]" value="recruiter"> Nur Recruiter<br>
            <input type="checkbox" name="visibility[]" value="familyandfriends"> Nur Familie & Freunde<br>
            <input type="checkbox" name="visibility[]" value="private"> Privat (Nur Admins)<br>
        </div>

        <label>Medien (mehrere Bilder möglich):</label>
        <input type="file" name="images[]" multiple accept="image/*">

        <button type="submit">Projekt speichern</button>
    </form>

    <a href="manage_projects.php">⬅ Zurück</a>
</body>
</html>
