<?php
include '../includes/auth.php';
check_auth(['client', 'admin']); // Admins dürfen auch rein

include '../views/header.php';
?>
<h1>Kunden-Bereich</h1>
<p>Hier können Kunden ihre Projekte verwalten.</p>

<h2>Abgeschlossene Projekte</h2>
<ul>
    <?php
    include '../includes/config.php';
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE status = 'completed' AND visibility IN ('public', 'client')");
    $stmt->execute();
    $projects = $stmt->fetchAll();
    
    foreach ($projects as $project): ?>
        <li>
            <h2><?= htmlspecialchars($project["title"]); ?></h2>
            <p><?= htmlspecialchars($project["description"]); ?></p>
        </li>
    <?php endforeach; ?>
</ul>

<?php include '../views/footer.php'; ?>
