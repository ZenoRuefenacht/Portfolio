<?php
include '../includes/auth.php';
check_auth( [ 'recruiter', 'client', 'familyandfriends' ] );

include '../views/header.php';
?>
<h1>Dashboard</h1>
<p>Willkommen im persönlichen Portfolio-Manager</p>
<a href = '../pages/projects.php'>Meine Projekte ansehen</a>
<?php include '../views/footer.php';
?>
