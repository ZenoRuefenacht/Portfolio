<?php
include '../includes/config.php';
include '../includes/auth.php';
check_auth(['admin']); // Nur Admins haben Zugriff

$message = "";

// Benutzer freigeben (approved = 1)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["approve_user"])) {
    $user_id = $_POST["user_id"];
    $stmt = $pdo->prepare("UPDATE users SET approved = 1 WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        $message = "✅ Benutzer wurde freigegeben.";
    } else {
        $message = "❌ Fehler beim Freigeben des Benutzers.";
    }
}

// Benutzer deaktivieren (approved = 0)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["disable_user"])) {
    $user_id = $_POST["user_id"];
    $stmt = $pdo->prepare("UPDATE users SET approved = 0 WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        $message = "✅ Benutzer wurde deaktiviert.";
    } else {
        $message = "❌ Fehler beim Deaktivieren des Benutzers.";
    }
}

// Benutzer löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_user"])) {
    $user_id = $_POST["user_id"];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$user_id])) {
        $message = "✅ Benutzer wurde gelöscht.";
    } else {
        $message = "❌ Fehler beim Löschen des Benutzers.";
    }
}

// Nicht freigegebene Benutzer abrufen (approved = 0)
$stmt = $pdo->prepare("SELECT * FROM users WHERE approved = 0 AND role != 'admin'");
$stmt->execute();
$pending_users = $stmt->fetchAll();

// Bereits freigegebene Benutzer abrufen (approved = 1)
$stmt = $pdo->prepare("SELECT * FROM users WHERE approved = 1 AND role != 'admin'");
$stmt->execute();
$approved_users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../views/navigation.php'; ?>

    <h1>Benutzerverwaltung</h1>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <h2>🔸 Benutzer freigeben</h2>
    <p>Diese Benutzer haben sich registriert, müssen aber noch freigegeben werden.</p>
    <ul>
        <?php if (empty($pending_users)): ?>
            <li>Keine Benutzer warten auf Freigabe.</li>
        <?php else: ?>
            <?php foreach ($pending_users as $user): ?>
                <li>
                    <strong><?= htmlspecialchars($user['email']) ?></strong> (<?= htmlspecialchars($user['role']) ?>)
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="approve_user" style="background-color: green; color: white;">Freigeben</button>
                        <button type="submit" name="delete_user" style="background-color: red; color: white;" onclick="return confirm('Möchtest du diesen Benutzer wirklich löschen?')">Löschen</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h2>🔹 Registrierte Benutzer</h2>
    <p>Diese Benutzer sind bereits freigegeben.</p>
    <ul>
        <?php if (empty($approved_users)): ?>
            <li>Keine freigegebenen Benutzer.</li>
        <?php else: ?>
            <?php foreach ($approved_users as $user): ?>
                <li>
                    <strong><?= htmlspecialchars($user['email']) ?></strong> (<?= htmlspecialchars($user['role']) ?>)
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="disable_user" style="background-color: orange; color: white;">Zugriff entziehen</button>
                        <button type="submit" name="delete_user" style="background-color: red; color: white;" onclick="return confirm('Möchtest du diesen Benutzer wirklich löschen?')">Löschen</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <a href="dashboard.php">⬅ Zurück zum Admin-Dashboard</a>
</body>
</html>
