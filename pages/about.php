<?php
include '../includes/config.php';
include '../includes/lang.php';

$role = $_SESSION["role"] ?? "public";

include '../views/header.php';
?>
<main>
<h1><?= t('about') ?></h1>
<p><?= t('about_intro') ?></p>

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

</main>
<?php include '../views/footer.php'; ?>
