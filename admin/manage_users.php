<?php
include '../includes/config.php';
include '../includes/auth.php';
check_auth(['admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];

    // Überprüfen, ob der Benutzer existiert
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "❌ Fehler: Benutzer mit ID $user_id existiert nicht.";
    } else {
        // Benutzer auf "approved = 1" setzen
        $stmt = $pdo->prepare("UPDATE users SET approved = 1 WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            echo "✅ Benutzer erfolgreich freigegeben!";
        } else {
            echo "❌ Fehler beim Freigeben des Benutzers.";
        }
    }
}

// Alle nicht freigegebenen Benutzer abrufen
$stmt = $pdo->query("SELECT * FROM users WHERE approved = 0");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
</head>
<body>
    <h1>Benutzerverwaltung</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?= htmlspecialchars($user['email']) ?> - 
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit">Freigeben</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
