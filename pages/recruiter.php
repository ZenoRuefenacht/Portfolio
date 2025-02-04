<?php
include '../includes/auth.php';
check_auth(['recruiter', 'admin']); // Admins dürfen auch rein

include '../views/header.php';
?>
<h1>Recruiter-Bereich</h1>
<p>Hier finden Recruiter alle Bewerberinformationen.</p>

<h2>Bewerberprofil</h2>
<p>[Platzhalter für Bewerberdaten]</p>

<h2>Portfolio</h2>
<p>[Platzhalter für Projekte, die Recruiter sehen dürfen]</p>

<?php include '../views/footer.php'; ?>
