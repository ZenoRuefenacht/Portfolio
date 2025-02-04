<?php
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Token überprüfen
    $stmt = $pdo->prepare("SELECT * FROM invitations WHERE token = ? AND used = 0");
    $stmt->execute([$token]);
    $invitation = $stmt->fetch();

    if ($invitation) {
        // Benutzer registrieren
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role, approved) VALUES (?, ?, ?, 0)");
        if ($stmt->execute([$invitation["email"], $password, $invitation["role"]])) {
            $pdo->prepare("UPDATE invitations SET used = 1 WHERE id = ?")->execute([$invitation["id"]]);
            echo "Registrierung erfolgreich. Ein Admin muss deinen Account freigeben.";
        } else {
            echo "Fehler bei der Registrierung.";
        }
    } else {
        echo "Ungültiges Token oder Einladung bereits verwendet.";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="./android-chrome-192x192.png">
    <link rel="shortcut icon" href="./android-chrome-192x192.png">
</head>
<body>
    <h1>Registrierung</h1>
    <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit">Registrieren</button>
    </form>
</body>
</html>
