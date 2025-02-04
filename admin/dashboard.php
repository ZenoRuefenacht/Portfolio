<?php
include '../includes/auth.php';
include '../includes/config.php';
check_auth(['admin']); // Nur Admins haben Zugriff

include '../views/navigation.php'; // Navigation einbinden
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin-Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <h1>Admin-Dashboard</h1>
    <p>Willkommen im Admin-Bereich. Hier kannst du Benutzer verwalten, Projekte bearbeiten und Systemeinstellungen vornehmen.</p>

    <h2>👤 Benutzerverwaltung</h2>
    <ul>
        <li><a href="manage_users.php">Benutzer freigeben, deaktivieren oder löschen</a></li>
        <li><a href="invite_user.php">Benutzer per E-Mail einladen</a></li>
    </ul>

    <h2>📂 Projektverwaltung</h2>
    <ul>
        <li><a href="manage_projects.php">Projekte verwalten (Hinzufügen, Bearbeiten, Löschen)</a></li>
    </ul>

    <h2>⚙ Systemeinstellungen</h2>
    <ul>
        <li><a href="../index.php">Zurück zur Startseite</a></li>
        <li><a href="../auth/logout.php" style="color: red;">Logout</a></li>
    </ul>

</body>
</html>
