<?php
include '../includes/auth.php';
check_auth( [ 'admin' ] );

include '../views/header.php';
?>
<h1>Admin-Dashboard</h1>
<p>Hier kannst du Benutzer verwalten und Einladungen erstellen.</p>
<ul>
<li><a href = 'invite_user.php'>Benutzer einladen</a></li>
<li><a href = 'manage_users.php'>Benutzer verwalten</a></li>
<li><a href = 'manage_project.php'>Projekt hinzufügen</a></li>
</ul>
<?php include '../views/footer.php';
?>
