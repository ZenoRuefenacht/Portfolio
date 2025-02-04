<?php
session_start();
include '../includes/config.php';

// Falls man schon eingeloggt ist, direkt zur Startseite weiterleiten
if (isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Benutzer abrufen
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = "Benutzer existiert nicht.";
    } elseif (!$user["approved"]) {
        $error = "Account ist noch nicht freigegeben.";
    } elseif (!password_verify($password, $user["password"])) {
        $error = "Passwort ist falsch.";
    } else {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = $user["role"];

        // Zur Startseite weiterleiten
        header("Location: ../index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="email" name="email" placeholder="E-Mail" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit">Einloggen</button>
    </form>
    
    <p>Noch kein Konto? Bitte kontaktiere den Administrator für eine Einladung.</p>
</body>
</html>
