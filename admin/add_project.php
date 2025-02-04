<?php
include '../includes/auth.php';
include '../includes/config.php';
check_auth(['admin']); // Nur Admins dürfen auf diese Seite zugreifen

// Fehler- und Erfolgsmeldungen
$message = "";

// Falls das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $status = $_POST["status"];
    $visibility = $_POST["visibility"];
    $media_url = "";

    // Datei-Upload (falls vorhanden)
    if (!empty($_FILES["media"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["media"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Erlaubte Dateitypen
        $allowed_types = ["jpg", "png", "jpeg", "gif", "mp4", "mov"];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES["media"]["tmp_name"], $target_file)) {
                $media_url = $target_file;
            } else {
                $message = "❌ Fehler beim Hochladen der Datei.";
            }
        } else {
            $message = "❌ Nur JPG, PNG, GIF, MP4, MOV erlaubt.";
        }
    }

    // Projekt in die Datenbank einfügen
    if ($message === "") {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, status, media_url, visibility, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt->execute([$title, $description, $category, $status, $media_url, $visibility])) {
            $message = "✅ Projekt erfolgreich hinzugefügt!";
        } else {
            $message = "❌ Fehler beim Speichern des Projekts.";
        }
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

        <label>Sichtbarkeit:</label>
        <select name="visibility">
            <option value="public">Öffentlich</option>
            <option value="client">Nur Kunden</option>
            <option value="recruiter">Nur Recruiter</option>
            <option value="familyandfriends">Nur Familie & Freunde</option>
        </select>

        <label>Medien (optional, Bild/Video):</label>
        <input type="file" name="media" accept="image/*,video/*">

        <button type="submit">Projekt speichern</button>
    </form>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
