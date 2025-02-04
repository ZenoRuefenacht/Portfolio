<?php
include '../includes/config.php';
include '../views/header.php';
session_start();

$role = $_SESSION["role"] ?? "public";
?>
<h1>Über mich</h1>
<p>Ich bin [Dein Name], ein leidenschaftlicher [Berufsbezeichnung].</p>

<?php if ($role == "recruiter"): ?>
    <h2>Berufliche Details</h2>
    <p>[Platzhalter für berufliche Qualifikationen]</p>
<?php elseif ($role == "client"): ?>
    <h2>Erfahrung</h2>
    <p>[Platzhalter für Projekte mit Kunden]</p>
<?php elseif ($role == "familyandfriends"): ?>
    <h2>Persönliche Infos</h2>
    <p>[Platzhalter für persönliche Details]</p>
<?php else: ?>
    <p>Mehr Infos erhalten Sie nach Anmeldung.</p>
<?php endif; ?>

<?php include '../views/footer.php'; ?>
