<?php
include '../includes/auth.php';
check_auth(['familyandfriends', 'admin']); // Admins dürfen auch rein

include '../views/header.php';
?>
<h1>Familie & Freunde</h1>
<p>Hier finden enge Bekannte persönliche Informationen.</p>

<h2>Private Inhalte</h2>
<p>[Platzhalter für private Informationen, die nur Familie & Freunde sehen dürfen]</p>

<h2>Fotos & Erinnerungen</h2>
<p>[Platzhalter für Bildergalerie oder Videos]</p>

<?php include '../views/footer.php'; ?>
