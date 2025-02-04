<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/config.php';
include '../includes/auth.php';
check_auth(['admin']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // PHPMailer sicherstellen

// POST-Anfrage bearbeiten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $role = $_POST["role"];

    try {
        // Überprüfen, ob die E-Mail bereits eine Einladung hat
        $stmt = $pdo->prepare("SELECT * FROM invitations WHERE email = ?");
        $stmt->execute([$email]);
        $existingInvitation = $stmt->fetch();

        if ($existingInvitation) {
            echo "❌ Diese E-Mail wurde bereits eingeladen.";
        } else {
            // Sicherstellen, dass Token generiert wird
            try {
                $token = bin2hex(random_bytes(32));
            } catch (Exception $e) {
                $token = md5(uniqid(mt_rand(), true)); // Fallback, falls random_bytes fehlschlägt
            }

            // Prüfen, ob das Token korrekt generiert wurde
            if (empty($token)) {
                die("❌ Fehler: Einladungstoken konnte nicht generiert werden.");
            }

           // Einladung in die Datenbank speichern
            $stmt = $pdo->prepare("INSERT INTO invitations (email, token, role) VALUES (?, ?, ?)");
            if ($stmt->execute([$email, $token, $role])) {
                $inviteLink = "https://zeno-ruefenacht.com/auth/register.php?token=$token";

                // E-Mail senden
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com'; // Dein SMTP-Host
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'zenoruefenacht1@gmail.com'; // Dein SMTP-Benutzer
                    $mail->Password   = 'dpvj emsj xnoy vutk'; // Dein SMTP-Passwort
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    // E-Mail-Daten
                    $mail->setFrom('zenoruefenacht1@gmail.com', 'Mein Portfolio');
                    $mail->addAddress($email);
                    $mail->Subject = 'Ihre Einladung zur Registrierung';
                    $mail->Body    = "Hallo,\n\nSie wurden eingeladen, sich für mein Portfolio zu registrieren.\n\nBitte klicken Sie auf den folgenden Link, um sich zu registrieren:\n\n$inviteLink\n\nViele Grüße";

                    if ($mail->send()) {
                        echo "✅ Einladung erfolgreich gesendet an $email.";
                    } else {
                        echo "❌ Fehler beim E-Mail-Versand: " . $mail->ErrorInfo;
                    }
                } catch (Exception $e) {
                    echo "❌ Fehler beim Senden der E-Mail: " . $e->getMessage();
                }
            } else {
                echo "❌ Fehler beim Speichern in der Datenbank.";
            }
        }
    } catch (Exception $e) {
        echo "❌ Allgemeiner Fehler: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer einladen</title>
</head>
<body>
    <h1>Benutzer einladen</h1>
    <form method="POST">
        <input type="email" name="email" placeholder="E-Mail-Adresse" required>
        <select name="role">
            <option value="recruiter">Recruiter</option>
            <option value="client">Client</option>
            <option value="familyandfriends">Family & Friends</option>
        </select>
        <button type="submit">Einladung senden</button>
    </form>
</body>
</html>
